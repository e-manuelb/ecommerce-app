<?php

namespace App\Services;

use App\Constants\DiscountType;
use App\Constants\OrderStatus;
use App\Constants\ProductType;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Carbon\Carbon;
use Exception;

readonly class OrderService
{
    public CartService $cartService;
    public CouponService $couponService;

    public function __construct(CartService $cartService, CouponService $couponService)
    {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
    }

    public function findByUuid(string $uuid): ?Order
    {
        return Order::query()->where('uuid', $uuid)->first();
    }

    /**
     * @throws Exception
     */
    public function storeFromRequest(CreateOrderRequest $request): Order
    {
        $total = $this->cartService->total();
        $discount = 0;
        $discounts = [];

        /** @var string[] $coupons */
        $couponCodes = $request->input('coupons');

        foreach ($couponCodes as $couponCode) {
            $coupon = $this->couponService->findByCode($couponCode);

            if (!$coupon) {
                throw new Exception('Invalid coupon code');
            }

            if (!$coupon->active) {
                throw new Exception('Inactive coupon code');
            }

            $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->expires_at);

            if ($expiresAt->isBefore(now())) {
                throw new Exception('Expired coupon code');
            }

            if ($coupon->min_subtotal_to_apply > $total) {
                throw new Exception("Coupon {$couponCode} not valid for your shopping cart");
            }

            if ($coupon->discount_type === DiscountType::PERCENTAGE) {
                $discount += $total * ($coupon->discount / 100);
            } else {
                $discount += $coupon->discount;
            }

            $discounts[] = [
                'coupon_code' => $couponCode,
                'discount' => $discount,
            ];
        }

        $orderAddress = [
            'street' => $request->input('address.street'),
            'number' => $request->input('address.number'),
            'complement' => $request->input('address.complement'),
            'city' => $request->input('address.city'),
            'state' => $request->input('address.state'),
            'zip_code' => $request->input('address.zip_code'),
            'country' => 'Brasil',
        ];

        $items = $this->cartService->all();

        $shippingFee = 20.00;

        if ($total >= 52.00 && $total <= 166.59) {
            $shippingFee = 15.00;
        }

        if ($total > 200) {
            $shippingFee = 0.00;
        }

        /** @var Order $order */
        $order = Order::query()->create([
            'subtotal' => $total,
            'total' => $total + $shippingFee - $discount,
            'additional_information' => json_encode([
                'shipping_fee' => $shippingFee,
                'discounts' => $discounts
            ])
        ]);

        foreach ($items as $item) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                'quantity' => $item["quantity"],
                'price' => $item["price"],
                'product_type' => $item["product_type"],
                'product_reference_uuid' => $item["uuid"],
            ]);

            if ($item['product_type'] == ProductType::PRODUCT) {
                /** @var Product $product */
                $product = Product::query()->where('uuid', $item["uuid"])->first();

                $product->stock->update([
                    'quantity' => $product->stock->quantity - $item["quantity"],
                ]);
            } else {
                /** @var ProductVariation $productVariation */
                $productVariation = ProductVariation::query()->where('uuid', $item["uuid"])->first();

                $productVariation->stock->update([
                    'quantity' => $productVariation->stock->quantity - $item["quantity"],
                ]);
            }
        }

        $orderAddress['order_id'] = $order->id;

        OrderAddress::query()->create($orderAddress);

        $this->cartService->clear();

        return $order;
    }

    public function changeStatus(Order $order, string $status): Order
    {
        $order->update([
            'status' => $status,
        ]);

        return $order;
    }
}

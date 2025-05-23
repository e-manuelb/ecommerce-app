<?php

namespace App\Services;

use App\Constants\ProductType;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;

readonly class OrderService
{
    public CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function storeFromRequest(CreateOrderRequest $request): Order
    {
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
        $total = $this->cartService->total();

        $shippingPrice = 20.00;

        if ($total >= 52.00 && $total <= 166.59) {
            $shippingPrice = 15.00;
        }

        if ($total > 200) {
            $shippingPrice = 0.00;
        }

        /** @var Order $order */
        $order = Order::query()->create([
            'total' => $total + $shippingPrice,
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
                    'quantity' =>  $product->stock->quantity - $item["quantity"],
                ]);
            } else {
                /** @var ProductVariation $productVariation */
                $productVariation = ProductVariation::query()->where('uuid', $item["uuid"])->first();

                $productVariation->stock->update([
                    'quantity' =>  $productVariation->stock->quantity - $item["quantity"],
                ]);
            }
        }

        $orderAddress['order_id'] = $order->id;

        OrderAddress::query()->create($orderAddress);

        $this->cartService->clear();

        return $order;
    }
}

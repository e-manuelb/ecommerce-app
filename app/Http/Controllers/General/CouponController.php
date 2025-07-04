<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Coupon\CreateCouponRequest;
use App\Services\CartService;
use App\Services\CouponService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public readonly CouponService $couponService;
    public readonly CartService $cartService;

    public function __construct(CouponService $couponService, CartService $cartService)
    {
        $this->couponService = $couponService;
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        return view('coupons.index', [
            'coupons' => $this->couponService->paginate()
        ]);
    }

    public function validate(Request $request): JsonResponse
    {
        if ($request->isNotFilled('code')) {
            return response()->json([
                'message' => 'Coupon can not be null or empty.'
            ], 422);
        }

        $code = $request->input('code', '');
        $totalCart = $this->cartService->total();
        $coupon = $this->couponService->findByCode($code);

        if (!$coupon) {
            return response()->json([
                'message' => 'Invalid coupon code.'
            ], 400);
        }

        if (!$coupon->active) {
            return response()->json([
                'message' => 'Inactive coupon code.'
            ], 400);
        }

        $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->expires_at);

        if ($expiresAt->isBefore(now())) {
            return response()->json([
                'message' => 'Expired coupon code.'
            ], 400);
        }

        if ($coupon->min_subtotal_to_apply > $totalCart) {
            return response()->json([
                'message' => 'Coupon not valid for your shopping cart'
            ], 400);
        }

        return response()->json([
            'message' => 'Coupon valid for your shopping cart',
            'data' => [
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'discount_type' => $coupon->discount_type,
            ]
        ]);
    }

    public function store(CreateCouponRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['active'] = intval($data['active']);

        $this->couponService->create($data);

        return redirect()
            ->route('coupons.index')
            ->with('success', "Cupom criado com sucesso!");
    }

    public function destroy(string $uuid): JsonResponse
    {
        $coupon = $this->couponService->findByUUID($uuid);

        $this->couponService->delete($coupon);

        return response()->json([
            'message' => 'Coupon removed successfully!'
        ]);
    }
}

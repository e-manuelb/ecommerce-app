<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\general\coupon\CreateCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    public readonly CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index(): View
    {
        return view('coupons.index', [
            'coupons' => $this->couponService->paginate()
        ]);
    }

    public function store(CreateCouponRequest $request): RedirectResponse
    {
        $this->couponService->create($request->validated());

        return redirect()
            ->route('coupons.index')
            ->with('success', "Cupom criado com sucesso!");
    }
}

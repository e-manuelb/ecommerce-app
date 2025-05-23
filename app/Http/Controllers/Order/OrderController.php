<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public readonly CartService $cartService;
    public readonly OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }


    public function confirmOrder(): View
    {
        return view('orders.confirm-order', [
            'items' => $this->cartService->all(),
            'total' => $this->cartService->total(),
        ]);
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->storeFromRequest($request);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order,
        ]);
    }
}

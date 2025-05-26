<?php

namespace App\Http\Controllers\Order;

use App\Constants\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use function PHPUnit\Framework\exactly;

class OrderController extends Controller
{
    public readonly CartService $cartService;
    public readonly OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index(): View
    {
        return view('orders.index', [
            'orders' => Order::query()->paginate()
        ]);
    }

    public function generateReceipt(string $orderUuid): Response
    {
        $order = $this->orderService->findByUuid($orderUuid);

        if (!$order) {
            abort(400);
        }

        $pdf = Pdf::loadView('orders.receipt', [
            'order' => $order,
            'items' => $order->orderItems,
            'address' => $order->orderAddress
        ]);

        return $pdf->download('invoice.pdf');
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
        try {
            $order = $this->orderService->storeFromRequest($request);

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => "Order can not be created due an failure. Error: {$exception->getMessage()}"
            ], 400);
        }
    }

    public function changeStatus(string $uuid, Request $request): JsonResponse
    {
        $order = $this->orderService->findByUuid($uuid);

        if (!$order) {
            return response()->json([
                'message' => "Order not found"
            ], 400);
        }

        $status = $request->input('status');

        $validStatuses = [
            OrderStatus::PENDING,
            OrderStatus::PROCESSING,
            OrderStatus::CANCELLED,
            OrderStatus::COMPLETED,
        ];

        if (!in_array($status, $validStatuses, true)) {
            return response()->json([
                'message' => "Invalid status"
            ], 422);
        }

        $this->orderService->changeStatus($order, $status);

        return response()->json([
            'message' => "Order status updated successfully"
        ]);
    }
}

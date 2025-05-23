<?php

namespace App\Http\Controllers\General;

use App\Constants\ProductType;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\ProductVariationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public readonly CartService $cartService;
    public readonly ProductService $productService;
    public readonly ProductVariationService $productVariationService;

    public function __construct(CartService $cartService, ProductService $productService, ProductVariationService $productVariationService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->productVariationService = $productVariationService;
    }

    public function index(): View
    {
        $items = $this->cartService->all();
        $total = $this->cartService->total();

        $shippingPrice = 20.00;

        if ($total >= 52.00 && $total <= 166.59) {
            $shippingPrice = 15.00;
        }

        if ($total > 200) {
            $shippingPrice = 0.00;
        }

        return view('cart.index', [
            'shippingPrice' => $shippingPrice,
            'items' => $items,
            'subtotal' => $total
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $this->cartService->add($request->input('product_uuid'), (int)$request->input('quantity', 1));

        return redirect()->route('cart.index')->with('success', 'Produto adicionado!');
    }

    public function update(Request $request): JsonResponse
    {
        $this->cartService->update($request->input('product_uuid'), (int)$request->input('quantity'));

        return response()->json([
            'message' => 'Item updated successfully!'
        ]);
    }

    public function remove(string $uuid): JsonResponse
    {
        $this->cartService->remove($uuid);

        return response()->json([
            'message' => 'Item removed successfully!'
        ]);
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return back()->with('success', 'Carrinho esvaziado!');
    }
}

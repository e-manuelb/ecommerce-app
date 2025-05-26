<?php

namespace App\Http\Controllers\Product;

use App\Constants\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public readonly ProductService $productService;
    public readonly StockService $stockService;
    public readonly CartService $cartService;

    public function __construct(ProductService $productService, StockService $stockService, CartService $cartService)
    {
        $this->productService = $productService;
        $this->stockService = $stockService;
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        return view('products.index', [
            'products' => $this->productService->paginate()
        ]);
    }

    public function show(String $uuid): View
    {
        return view('products.show', [
            'product' => $this->productService->findByUUID($uuid)
        ]);
    }

    public function edit(String $uuid): View
    {
        return view('products.form', [
           'product' => $this->productService->findByUUID($uuid)
        ]);
    }

    public function create(): View
    {
        return view('products.form');
    }

    public function addToCart(String $uuid): RedirectResponse
    {
        $this->cartService->add($uuid, ProductType::PRODUCT, 1);

        return back()->with('success', 'Produto adicionado ao carrinho com sucesso!');
    }

    public function store(CreateProductRequest $request): RedirectResponse
    {
        $this->productService->storeFromRequest($request);

        return redirect()
            ->route('products.index')
            ->with('success', "Produto criado com sucesso!");
    }

    public function update(UpdateProductRequest $request, String $uuid): RedirectResponse
    {
        $product = $this->productService->findByUUID($uuid);

        $this->productService->updateFromRequest($product, $request);

        return redirect()
            ->route('products.index')
            ->with('success', "Produto atualizado com sucesso!");
    }

    public function destroy(String $uuid): JsonResponse
    {
        $product = $this->productService->findByUUID($uuid);

        if (!$product) {
            return response()->json([
               'message' => 'Produto não encontrado!'
            ], 400);
        }

        $this->productService->delete($product);

        return response()->json([
            'message' => 'Produto removido com sucesso!'
        ]);
    }
}

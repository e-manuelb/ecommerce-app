<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Http\Requests\product\CreateProductRequest;
use App\Http\Requests\product\UpdateProductRequest;
use App\Services\ProductService;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public readonly ProductService $productService;
    public readonly StockService $stockService;

    public function __construct(ProductService $productService, StockService $stockService)
    {
        $this->productService = $productService;
        $this->stockService = $stockService;
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

    public function destroy(String $uuid): RedirectResponse
    {
        $product = $this->productService->findByUUID($uuid);

        $this->productService->delete($product);

        return redirect()
            ->route('products.index')
            ->with('success', "Produto removido com sucesso!");
    }
}

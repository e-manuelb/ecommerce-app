<?php

namespace App\Http\Controllers\Product;

use App\Constants\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductVariationRequest;
use App\Http\Requests\Product\UpdateProductVariationRequest;
use App\Models\ProductVariation;
use App\Services\CartService;
use App\Services\ProductVariationService;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariationController extends Controller
{
    public readonly ProductVariationService $productVariationService;
    public readonly StockService $stockService;
    public readonly CartService $cartService;

    public function __construct(ProductVariationService $productVariationService, StockService $stockService, CartService $cartService)
    {
        $this->productVariationService = $productVariationService;
        $this->stockService = $stockService;
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        return view('product-variations.index', [
            'productVariations' => $this->productVariationService->paginate()
        ]);
    }

    public function show(String $uuid): View
    {
        return view('product-variations.show', [
            'productVariation' => $this->productVariationService->findByUUID($uuid)
        ]);
    }

    public function create(): View
    {
        return view('product-variations.form');
    }

    public function addToCart(String $uuid): RedirectResponse
    {
        $this->cartService->add($uuid, ProductType::PRODUCT_VARIATION, 1);

        return back()->with('success', 'Produto adicionado ao carrinho com sucesso!');
    }

    public function store(CreateProductVariationRequest $request): RedirectResponse
    {
        $this->productVariationService->storeFromRequest($request);

        return redirect()
            ->route('product-variations.index')
            ->with('success', "Produto criado com sucesso!");
    }

    public function edit(String $uuid): View
    {
        $productVariation = $this->productVariationService->findByUUID($uuid);

        return view('product-variations.form', [
            'productVariation' => $productVariation
        ]);
    }

    public function update(UpdateProductVariationRequest $request, String $uuid): RedirectResponse
    {
        $productVariation = $this->productVariationService->findByUUID($uuid);

        $this->productVariationService->updateFromRequest($productVariation, $request);

        return redirect()
            ->route('product-variations.index')
            ->with('success', "Produto criado com sucesso!");
    }

    public function destroy(String $uuid): RedirectResponse
    {
        $productVariation = $this->productVariationService->findByUUID($uuid);

        $this->productVariationService->delete($productVariation);

        return redirect()
            ->route('product-variations.index')
            ->with('success', "Produto removido com sucesso!");
    }
}

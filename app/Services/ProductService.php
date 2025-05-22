<?php

namespace App\Services;

use App\Constants\ProductTypes;
use App\Http\Requests\product\CreateProductRequest;
use App\Http\Requests\product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProductService
{
    private StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function paginate(): LengthAwarePaginator
    {
        return Product::query()->paginate();
    }

    public function create(array $data): Product
    {
        return Product::query()->create($data);
    }

    public function storeFromRequest(CreateProductRequest $request): Product
    {
        $product = $this->create($request->validated());

        if ($request->has('quantity') && $request->input('quantity') > 0) {
            $this->stockService->create([
                'product_type' => ProductTypes::PRODUCT,
                'product_reference_uuid' => $product->uuid,
                'quantity' => $request->input('quantity'),
            ]);
        }

        return $product;
    }

    public function findByUUID(string $uuid): ?Product
    {
        return Product::query()->where('uuid', $uuid)->first();
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function updateFromRequest(Product $product, UpdateProductRequest $request): Product
    {
        $this->update($product, $request->validated());

        $stock = $product->stock;

        if ($stock != null && $request->input('quantity') > 0) {
            $stock->update([
                'quantity' => $request->input('quantity')
            ]);
        }

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}

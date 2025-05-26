<?php

namespace App\Services;

use App\Constants\ProductType;
use App\Http\Requests\Product\CreateProductVariationRequest;
use App\Http\Requests\Product\UpdateProductVariationRequest;
use App\Models\ProductVariation;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProductVariationService
{
    public StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }


    public function paginate(): LengthAwarePaginator
    {
        return ProductVariation::query()->paginate();
    }

    public function create(array $data): ProductVariation
    {
        return ProductVariation::query()->create($data);
    }

    public function findByUUID(string $uuid): ?ProductVariation
    {
        return ProductVariation::query()->where('uuid', $uuid)->first();
    }

    public function storeFromRequest(CreateProductVariationRequest $request): ProductVariation
    {
        $product = $this->create($request->validated());

        if ($request->has('quantity') && $request->input('quantity') > 0) {
            $this->stockService->create([
                'product_type' => ProductType::PRODUCT_VARIATION,
                'product_reference_uuid' => $product->uuid,
                'quantity' => $request->input('quantity'),
            ]);
        }

        return $product;
    }

    public function update(ProductVariation $productVariation, array $data): ProductVariation
    {
        $productVariation->update($data);

        return $productVariation;
    }

    public function updateFromRequest(ProductVariation $productVariation, UpdateProductVariationRequest $request): ProductVariation
    {
        $productVariation->update($request->validated());

        if ($request->has('quantity') && $request->input('quantity') > 0) {

            $stock = $productVariation->stock;

            if ($stock == null) {
                $this->stockService->create([
                    'product_type' => ProductType::PRODUCT_VARIATION,
                    'product_reference_uuid' => $productVariation->uuid,
                    'quantity' => $request->input('quantity')
                ]);
            } else {
                $stock->update([
                    'quantity' => $request->input('quantity')
                ]);
            }
        }

        return $productVariation;
    }

    public function delete(ProductVariation $productVariation): void
    {
        $productVariation->delete();
    }
}

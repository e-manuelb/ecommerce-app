<?php

namespace App\Services;

use App\Constants\ProductType;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

readonly class CartService
{
    protected string $key;

    public function __construct()
    {
        $this->key = 'cart.items';
    }

    public function all(): Collection
    {
        $items = collect(Session::get('cart.items'));

        return $items->filter(function ($item) {
            if ($item['product_type'] == ProductType::PRODUCT) {
                return Product::query()->where('uuid', $item['uuid'])->exists();
            }

            if ($item['product_type'] == ProductType::PRODUCT_VARIATION) {
                return ProductVariation::query()->where('uuid', $item['uuid'])->exists();
            }

            return true;
        });
    }

    public function add(String $uuid, String $type, int $quantity = 1): void
    {
        $cart = $this->all();
        $item = $cart->firstWhere('uuid', $uuid);

        $product = $type == ProductType::PRODUCT ? Product::query()->where('uuid', $uuid)->first() : ProductVariation::query()->where('uuid', $uuid)->first();

        if (!$product) {
            return;
        }

        if ($item) {
            $cart = $cart->map(function ($item) use ($uuid, $quantity) {
                return $item['uuid'] == $uuid ? array_merge($item, ['quantity' => $item['quantity'] + $quantity]) : $item;
            });
        } else {
            $cart->push([
                'uuid' => $uuid,
                'product_type' => $type,
                'quantity' => $quantity,
                'price' => $product->price,
                'product' => $product,
                'total_stock' => $product->stock->quantity,
            ]);
        }

        Session::put($this->key, $cart->toArray());
    }

    public function remove(string $uuid): void
    {
        $cart = $this->all()->reject(fn($item) => $item['uuid'] == $uuid);

        Session::put($this->key, $cart->toArray());
    }

    public function clear(): void
    {
        Session::remove($this->key);
    }

    public function update(string $uuid, int $quantity): void
    {
        $cart = $this->all()->map(function ($item) use ($uuid, $quantity) {
            return $item['uuid'] == $uuid ? array_merge($item, ['quantity' => $quantity]) : $item;
        });

        Session::put($this->key, $cart->toArray());
    }

    public function total(): float
    {
        return $this->all()->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getCurrentQuantityByProduct(String $uuid): int
    {
        $current = $this->all()->firstWhere('uuid', $uuid);

        if (!isset($current)) {
            return 0;
        }

        return $current['quantity'];
    }
}

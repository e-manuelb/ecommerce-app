@php use App\Models\Product;use App\Models\ProductVariation;use App\Utils\CurrencyUtil; @endphp
@extends('layouts.app')

@section('content')
    <?php /** @var Product $product */ ?>
    <div class="container border p-3 bg-body-tertiary">
        <p class="h3">{{ $product->id }} - {{ $product->name }}</p>
        <p>SKU: {{ $product->sku }}</p>
        <p>Descrição: {{ $product->description }}</p>
        <p>Preço: {{ CurrencyUtil::formatBRL($product->price) }}</p>
        <p>Estoque: {{ !!$product->stock ? $product->stock->quantity : 0}}</p>

        <?php if (count($product->productVariations) > 0): ?>
            <?php $productVariations = $product->productVariations ?>
            <p class="h4">Variações</p>
            <div class="accordion accordion-flush" id="accordion">
                @foreach($productVariations as $productVariation)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapsePv-{{ $productVariation->id }}"
                                    aria-expanded="false"
                                    aria-controls="collapsePv-{{ $productVariation->id }}">
                                {{ $productVariation->id }} - {{ $productVariation->name }}
                            </button>
                        </h2>
                        <div id="collapsePv-{{ $productVariation->id }}"
                             class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="h3">{{ $productVariation->id }} - {{ $productVariation->name }}</p>
                                <p>Identificador: {{ $productVariation->sku }}</p>
                                <p>Descrição: {{ $productVariation->description }}</p>
                                <p>Preço: {{ CurrencyUtil::formatBRL($productVariation->price) }}</p>
                                <p>Estoque: {{ !!$productVariation->stock ? $productVariation->stock->quantity : 0}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        <?php endif; ?>
    </div>
@endsection

@php use App\Models\Product;use App\Models\ProductVariation;use App\Utils\CurrencyUtil; @endphp
@extends('layouts.app')

@section('content')
    <?php /** @var ProductVariation $productVariation */ ?>
    <div class="container border p-3 bg-body-tertiary">
        <p class="h3">{{ $productVariation->id }} - {{ $productVariation->name }}</p>
        <p>SKU: {{ $productVariation->sku }}</p>
        <p>Descrição: {{ $productVariation->description }}</p>
        <p>Preço: {{ CurrencyUtil::formatBRL($productVariation->price) }}</p>
        <p>Estoque: {{ !!$productVariation->stock ? $productVariation->stock->quantity : 0}}</p>
    </div>
@endsection

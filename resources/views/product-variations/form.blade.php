@php
    use App\Models\Product;
    use App\Models\ProductVariation;
@endphp
@extends('layouts.app')

@section('content')
    <?php /** @var ProductVariation $productVariation */ ?>
    <?php $hasProductVariation = !!isset($productVariation) ?>

    <div class="container border p-4 bg-body-tertiary">
        <form
                action="{{ $hasProductVariation ? route('product-variations.update', $productVariation->uuid) : route('product-variations.store') }}"
                method="POST">
            @if($hasProductVariation)
                @method("PUT")
            @endif

            <div class="row mb-3">
                <div class="col">
                    <label for="product_id" class="form-label">Produto Original</label>
                    <select class="form-select" id="product_id" name="product_id">
                        @foreach(Product::query()->get(['id', 'name', 'sku']) as $simplifiedProduct)
                            @if($hasProductVariation && $simplifiedProduct->id == $productVariation->product_id)
                                <option selected
                                        value="{{ $productVariation->product->id }}">{{ "{$productVariation->product->sku} - {$productVariation->product->name}" }}</option>
                            @else
                                <option
                                        value="{{$simplifiedProduct->id}}">{{ "{$simplifiedProduct->sku} - {$simplifiedProduct->name}" }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($errors->has('product_id'))
                        <small id="productIdHelp"
                               class="form-text text-danger">{{ $errors->first('product_id') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="code" class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" id="name"
                           value="{{ $productVariation->name ?? old('name') }}">
                    @if($errors->has('name'))
                        <small id="nameHelp" class="form-text text-danger">{{ $errors->first('name') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="sku" class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" id="sku"
                           value="{{ $productVariation->sku ?? old('sku') }}">
                    @if($errors->has('sku'))
                        <small id="skuHelp" class="form-text text-danger">{{ $errors->first('sku') }}</small>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="description" class="form-label">Descrição</label>
                    <input type="text" name="description" class="form-control" id="description"
                           value="{{ $productVariation->description ?? old('description') }}">
                    @if($errors->has('description'))
                        <small id="descriptionHelp"
                               class="form-text text-danger">{{ $errors->first('description') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="price" class="form-label">Preço</label>
                    <input type="number" step="any" min="0.1" name="price" class="form-control" id="price"
                           value="{{ $productVariation->price ?? old('price') }}">
                    @if($errors->has('price'))
                        <small id="priceHelp" class="form-text text-danger">{{ $errors->first('price') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="quantity" class="form-label">Quantidade</label>
                    <input type="number" step="any" min="1" name="quantity" class="form-control" id="quantity"
                           value="{{ $productVariation->stock->quantity ?? old('quantity') }}">
                    @if($errors->has('quantity'))
                        <small id="quantityHelp" class="form-text text-danger">{{ $errors->first('quantity') }}</small>
                    @endif
                </div>
            </div>
            <div class="mt-3">
                <button type="submit"
                        class="btn btn-primary">{{ $hasProductVariation ? "Atualizar" : "Criar" }}</button>
            </div>
        </form>
    </div>
@endsection

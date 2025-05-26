@php use App\Models\Product; @endphp
@extends('layouts.app')

@section('content')
    <?php /** @var Product $product */ ?>
    <?php $hasProduct = !!isset($product) ?>


    <div class="container border p-4 bg-body-tertiary">
        <form action="{{ $hasProduct ? route('products.update', $product->uuid) : route('products.store') }}" method="POST">
            @if($hasProduct)
                @method("PUT")
            @endif

            <div class="row mb-3">
                <div class="col">
                    <label for="code" class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $product->name ?? old('name') }}">
                    @if($errors->has('name'))
                        <small id="nameHelp" class="form-text text-danger">{{ $errors->first('name') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="sku" class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" id="sku" value="{{ $product->sku ?? old('sku') }}">
                    @if($errors->has('sku'))
                        <small id="skuHelp" class="form-text text-danger">{{ $errors->first('sku') }}</small>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="description" class="form-label">Descrição</label>
                    <input type="text" name="description" class="form-control" id="description"
                           value="{{ $product->description ?? old('description') }}">
                    @if($errors->has('description'))
                        <small id="descriptionHelp" class="form-text text-danger">{{ $errors->first('description') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="price" class="form-label">Preço</label>
                    <input type="number" step="any" min="1" name="price" class="form-control" id="price" value="{{ $product->price ?? old('price') }}">
                    @if($errors->has('price'))
                        <small id="priceHelp" class="form-text text-danger">{{ $errors->first('price') }}</small>
                    @endif
                </div>
                <div class="col">
                    <label for="quantity" class="form-label">Quantidade</label>
                    <input type="number" step="any" min="1" name="quantity" class="form-control" id="quantity" value="{{ $product->stock->quantity ?? old('quantity') }}">
                    @if($errors->has('quantity'))
                        <small id="quantityHelp" class="form-text text-danger">{{ $errors->first('quantity') }}</small>
                    @endif
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">{{ $hasProduct ? "Atualizar" : "Criar"}}</button>
            </div>
        </form>
    </div>
@endsection

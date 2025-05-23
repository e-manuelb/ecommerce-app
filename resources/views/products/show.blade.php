@php
    use App\Models\Product;
    use App\Utils\CurrencyUtil;
@endphp
@extends('layouts.app')

@section('content')
    <?php /** @var Product $product */ ?>
    <div class="container border p-3 bg-body-tertiary">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="h2">Informações</p>
            <button type="button" class="btn btn-success btn-lg add-to-cart-product-button"
                    data-id="{{ $product->uuid }}">
                <i class="bi bi-cart"></i>
            </button>

            <form id="add-product-to-cart-{{ $product->uuid }}"
                  action="{{ route('products.add-to-cart', $product->uuid) }}"
                  method="POST"
                  style="display: none;">
                @csrf
                @method('POST')
            </form>
        </div>
        <hr>
        <div>
            <dt class="col-sm-3">Produto</dt>
            <dd class="col-sm-9">{{ $product->name }}</dd>

            <dt class="col-sm-3">SKU</dt>
            <dd class="col-sm-9">{{ $product->sku }}</dd>

            <dt class="col-sm-3">Descrição</dt>
            <dd class="col-sm-9">{{ $product->description }}</dd>

            <dt class="col-sm-3">Preço</dt>
            <dd class="col-sm-9">{{ CurrencyUtil::formatBRL($product->price) }}</dd>

            <dt class="col-sm-3">Estoque</dt>
            <dd class="col-sm-9">{{ !!$product->stock ? $product->stock->quantity : 0}}</dd>

            <?php if (count($product->productVariations)): ?>
            <hr>

            <p class="h4 mb-3">Variações</p>

                <?php $productVariations = $product->productVariations ?>
            @foreach($productVariations as $productVariation)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $productVariation->name }}
                            - {{ CurrencyUtil::formatBRL($productVariation->price) }} {{ " - {$productVariation->stock->quantity} em estoque" }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $productVariation->sku }}</h6>
                        <p class="text">
                            {{ $productVariation->description }}
                        </p>
                        <div class="text-end">
                            <button type="button" class="btn btn-success btn-lg add-to-cart-product-variation-button"
                                    data-id="{{ $productVariation->uuid }}">
                                <i class="bi bi-cart"></i>
                            </button>

                            <form id="add-product-variation-to-cart-{{ $productVariation->uuid }}"
                                  action="{{ route('product-variations.add-to-cart', $productVariation->uuid) }}"
                                  method="POST"
                                  style="display: none;">
                                @csrf
                                @method('POST')
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            <?php endif; ?>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-to-cart-product-button').forEach(button => {
            button.addEventListener('click', function (e) {
                const id = button.getAttribute('data-id');
                const form = document.getElementById('add-product-to-cart-' + id);

                Swal.fire({
                    title: 'Tem certeza?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Sim, adicionar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.add-to-cart-product-variation-button').forEach(button => {
            button.addEventListener('click', function (e) {
                const id = button.getAttribute('data-id');
                const form = document.getElementById('add-product-variation-to-cart-' + id);

                Swal.fire({
                    title: 'Tem certeza?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Sim, adicionar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

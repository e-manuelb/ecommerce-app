@php
    use App\Models\ProductVariation;
    use App\Utils\CurrencyUtil;
@endphp
@extends('layouts.app')

@section('content')
    <?php /** @var ProductVariation $productVariation */ ?>
    <div class="container border p-3 bg-body-tertiary">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="h2">Informações</p>
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
        <hr>
        <div>
            <dt class="col-sm-3">Produto</dt>
            <dd class="col-sm-9">{{ $productVariation->name }}</dd>

            <dt class="col-sm-3">SKU</dt>
            <dd class="col-sm-9">{{ $productVariation->sku }}</dd>

            <dt class="col-sm-3">Descrição</dt>
            <dd class="col-sm-9">{{ $productVariation->description }}</dd>

            <dt class="col-sm-3">Preço</dt>
            <dd class="col-sm-9">{{ CurrencyUtil::formatBRL($productVariation->price) }}</dd>

            <dt class="col-sm-3">Estoque</dt>
            <dd class="col-sm-9">{{ !!$productVariation->stock ? $productVariation->stock->quantity : 0}}</dd>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

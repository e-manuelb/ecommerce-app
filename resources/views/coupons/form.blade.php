@php use App\Constants\DiscountType; @endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-4 bg-body-tertiary">
        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="code" class="form-label">Código</label>
                    <input type="text" name="code" class="form-control" id="code" value="{{ old('code') }}" required>
                </div>
                <div class="col">
                    <label for="type" class="form-label">Tipo</label>
                    <input type="text" name="type" class="form-control" id="type" value="{{ old('type') }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="discount" class="form-label">Desconto</label>
                    <input type="number" step="any" min="0" name="discount" class="form-control" id="discount"
                           value="{{ old('discount') }}" required>
                </div>
                <div class="col">
                    <label for="discount_type" class="form-label">Tipo de Desconto</label>
                    <select name="discount_type" class="form-select" id="discount_type" required>
                        @foreach(DiscountType::ALL as $discountType)
                            <option value="{{ $discountType }}" label="{{ DiscountType::translate($discountType) }}"></option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="min_value_to_apply" class="form-label">Valor mínimo para aplicar desconto</label>
                    <input type="number" step="any" min="0" name="min_value_to_apply" class="form-control" id="min_value_to_apply" value="{{ old('min_value_to_apply') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <input id="active" name="active" type="checkbox" class="form-check-input" value="{{ old('active') }}">
                <label for="active" class="form-check-label">Ativo</label>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Criar</button>
            </div>
        </form>
    </div>
@endsection
<script>
    const discountInput = document.getElementById('discount');

    document.addEventListener('DOMContentLoaded', () => {
        const discountType = document.getElementById('discount_type');

        console.log(discountInput, discountType)

        discountType.onchange = () => {
            if (discountType.value === 'AMOUNT') {
                discountInput.setAttribute('max', 100);
            } else {
                discountInput.removeAttribute('max');
            }
        };
    });
</script>

@php use App\Constants\DiscountType; @endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-4 bg-body-tertiary">
        <form action="{{ route('coupons.store') }}" method="POST">
            <div class="row mb-3">
                <div class="col">
                    <label for="code" class="form-label">Código</label>
                    <input
                        type="text"
                        name="code"
                        id="code"
                        class="form-control"
                        value="{{ old('code') }}"
                        required
                    >
                    @if($errors->has('code'))
                        <small id="codeHelp" class="form-text text-danger">
                            {{ $errors->first('code') }}
                        </small>
                    @endif
                </div>
                <div class="col">
                    <label for="type" class="form-label">Tipo</label>
                    <input
                        type="text"
                        name="type"
                        id="type"
                        class="form-control"
                        value="{{ old('type') }}"
                        required
                    >
                    @if($errors->has('type'))
                        <small id="typeHelp" class="form-text text-danger">
                            {{ $errors->first('type') }}
                        </small>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="discount" class="form-label">Desconto</label>
                    <input
                        type="number"
                        name="discount"
                        id="discount"
                        step="any"
                        min="0"
                        class="form-control"
                        value="{{ old('discount') }}"
                        required
                    >
                    @if($errors->has('discount'))
                        <small id="discountHelp" class="form-text text-danger">
                            {{ $errors->first('discount') }}
                        </small>
                    @endif
                </div>
                <div class="col">
                    <label for="discount_type" class="form-label">Tipo de Desconto</label>
                    <select
                        name="discount_type"
                        id="discount_type"
                        class="form-select"
                        required
                    >
                        <option value="" disabled {{ old('discount_type') ? '' : 'selected' }}>Selecione...</option>
                        @foreach(DiscountType::ALL as $dt)
                            <option value="{{ $dt }}" {{ old('discount_type') === $dt ? 'selected' : '' }}>
                                {{ DiscountType::translate($dt) }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('discount_type'))
                        <small id="discountTypeHelp" class="form-text text-danger">
                            {{ $errors->first('discount_type') }}
                        </small>
                    @endif
                </div>
                <div class="col">
                    <label for="min_subtotal_to_apply" class="form-label">
                        Valor mínimo para aplicar desconto
                    </label>
                    <input
                        type="number"
                        name="min_subtotal_to_apply"
                        id="min_subtotal_to_apply"
                        step="any"
                        min="0"
                        class="form-control"
                        value="{{ old('min_subtotal_to_apply') }}"
                        required
                    >
                    @if($errors->has('min_subtotal_to_apply'))
                        <small id="minSubtotalHelp" class="form-text text-danger">
                            {{ $errors->first('min_subtotal_to_apply') }}
                        </small>
                    @endif
                </div>
                <div class="col">
                    <label for="expires_at" class="form-label">Expira em</label>
                    <input type="date" name="expires_at" class="form-control" id="expires_at">
                    @if($errors->has('expires_at'))
                        <small id="expiresAtHelp" class="form-text text-danger">
                            {{ $errors->first('expires_at') }}
                        </small>
                    @endif
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="active" value="0">
                <input
                    id="active"
                    name="active"
                    type="checkbox"
                    class="form-check-input"
                    value="1"
                    {{ old('active') ? 'checked' : '' }}
                >
                <label for="active" class="form-check-label">Ativo</label>
                @if($errors->has('active'))
                    <small id="activeHelp" class="form-text text-danger">
                        {{ $errors->first('active') }}
                    </small>
                @endif
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Criar</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const discountInput = document.getElementById('discount');
            const discountType = document.getElementById('discount_type');

            discountType.onchange = () => {
                if (discountType.value === 'PERCENTAGE') {
                    discountInput.setAttribute('max', 100);
                    if (discountInput.value > 100) {
                        discountInput.value = 100;
                    }
                } else {
                    discountInput.removeAttribute('max');
                }
            };
        });
    </script>
@endpush

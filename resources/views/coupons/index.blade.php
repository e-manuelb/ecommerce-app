@php use App\Models\Coupon;use App\Utils\CurrencyUtil;use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-3 bg-body-tertiary">
        <div>
            <p class="h3">Cupons</p>
        </div>
        <div class="text-end mb-3">
            <a href="{{ route('coupons.create') }}" class="btn btn-success">Criar</a>
        </div>
        <div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Desconto</th>
                    <th scope="col">Tipo de Desconto</th>
                    <th scope="col">Subtotal Mínimo para Aplicação</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">Expira em</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var Coupon[] $coupons */ ?>
                @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ Str::limit($coupon->code, 30) }}</td>
                        <td>{{ Coupon::formatByDiscountType($coupon->discount, $coupon->discount_type) }}</td>
                        <td>{{ $coupon->discount_type  }}</td>
                        <td>{{ CurrencyUtil::formatBRL($coupon->min_subtotal_to_apply) }}</td>
                        <td>{{ !!$coupon->active ? "Sim" : "Não" }}</td>
                        <td>{{ $coupon->expires_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $coupons->links() }}
    </div>
@endsection

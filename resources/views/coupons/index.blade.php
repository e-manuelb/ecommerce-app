@php use App\Models\Coupon;use App\Utils\CurrencyUtil;use Carbon\Carbon;use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-3 bg-body-tertiary">
        <div>
            <p class="h3">Cupons</p>
        </div>
        <div class="text-end mb-3">
            <a href="{{ route('coupons.create') }}" class="btn btn-success">Criar</a>
        </div>
        @if(!$coupons || count($coupons) == 0)
            <div class="text-center align-middle p-3">
                <p class="h4">Não há Cupons disponíveis.</p>
            </div>
        @else
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
                        <th scope="col" class="text-center">Ações</th>
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
                            <td>{{ Carbon::createFromFormat('Y-m-d H:i:s', $coupon->expires_at)->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn btn-group">
                                    <button class="btn btn-danger "
                                            onclick="deleteCoupon('{{ route('coupons.destroy', $coupon->uuid) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection
<script>
    async function deleteCoupon(route) {
        const result = await Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await axios.delete(route, {
                headers: {
                    'Accept': 'application/json'
                }
            })

            if (response.status !== 200) {
                throw new Error('Erro ao remover cupom');
            }

            Swal.fire('Removido!', 'Cupom removido com sucesso.', 'success').then(() => window.location.reload());
        } catch (exception) {
            Swal.fire('Erro!', exception.message, 'error').then(() => window.location.reload());
        }
    }
</script>

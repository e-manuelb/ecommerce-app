@php use App\Constants\OrderStatus;use App\Models\Order;use App\Utils\CurrencyUtil; @endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-3 bg-body-tertiary">
        <p class="h3">Pedidos</p>
        <div class="mt-5">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">UUID</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var Order[] $orders */ ?>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->uuid }}</td>
                        <td>{{ CurrencyUtil::formatBRL($order->subtotal) }}</td>
                        <td>{{ CurrencyUtil::formatBRL($order->total) }}</td>
                        <td>{{ OrderStatus::translate($order->status) }}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-secondary" onclick="generateReceipt(
                                '{{ route('orders.receipt', $order->uuid) }}', '{{ $order->uuid }}'
                            )">
                                <i class="bi bi-receipt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script>
    async function generateReceipt(route, orderUuid) {
        const response = await axios.get(route, {
            responseType: 'blob',
            headers: {
                'Accept': 'application/pdf'
            }
        });

        const blob = new Blob([response.data], { type: 'application/pdf' });

        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `recibo-${orderUuid}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);
    }
</script>

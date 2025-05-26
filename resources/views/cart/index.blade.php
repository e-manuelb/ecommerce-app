@php use App\Utils\CurrencyUtil; @endphp
@extends('layouts.app')

@section('content')
    <div class="container p-3 bg-body-tertiary">
        <p class="h3 mb-3">Carrinho</p>

        @if(count($items) == 0)
            <div class="text-center align-middle p-3">
                <p class="h4">Não há itens no carrinho.</p>
            </div>
        @else
            <div>
                <div class="row mb-3">
                    <div class="col-10">
                        <div class="card">
                            <div class="card-body">
                                <table class="table .table-borderless align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Qtd.</th>
                                        <th scope="col">Preço</th>
                                        <th scope="col">Total</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)
                                            <?php $product = $item['product'] ?>
                                        <tr>
                                            <td>
                                                <div class="card border-0">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $product->name }}</h5>
                                                        <h6 class="card-subtitle mb-2 text-muted">{{ $product->sku }}</h6>
                                                        <p class="text">
                                                            {{ $product->description }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Quantidade">
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary btn-sm"
                                                        onclick="changeQuantity('{{ route('cart.update') }}', '{{ $item['uuid'] }}', {{ $item['quantity'] - 1 }})"
                                                        @if($item['quantity'] == 1) disabled @endif
                                                    >−
                                                    </button>

                                                    <span class="btn btn-outline-secondary btn-sm disabled">
                                                        {{ $item['quantity'] }}
                                                    </span>
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary btn-sm"
                                                        onclick="changeQuantity('{{ route('cart.update') }}', '{{ $item['uuid'] }}', {{ $item['quantity'] + 1 }})"
                                                        @if($item['total_stock'] == $item['quantity']) disabled @endif
                                                    >+
                                                    </button>
                                                </div>
                                            </td>

                                            <td>
                                                {{ CurrencyUtil::formatBRL($product->price) }}
                                            </td>
                                            <td>
                                                {{ CurrencyUtil::formatBRL($product->price * $item['quantity']) }}
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                    onclick="confirmRemove('{{ route('cart.remove', $product->uuid) }}', this)"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Resumo</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Subtotal</h6>
                                <p class="card-text">
                                    {{ CurrencyUtil::formatBRL($subtotal) }}
                                </p>
                                <h6 class="card-subtitle mb-2 text-muted">Frete</h6>
                                <p class="card-text">
                                    @if($shippingPrice == 0)
                                        <del>{{ CurrencyUtil::formatBRL($shippingPrice) }}</del>
                                    @else
                                        {{ CurrencyUtil::formatBRL($shippingPrice) }}
                                    @endif
                                </p>
                                <h6 class="card-subtitle mb-2">Total</h6>
                                <p class="card-text">
                                    {{ CurrencyUtil::formatBRL($shippingPrice + $subtotal) }}
                                </p>
                                <form id="createOrder">
                                    <a href="{{ route('orders.confirm-order') }}" class="btn btn-primary">Finalizar compra</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
<script>
    async function confirmRemove(route, button) {
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
                    'Accept': 'application/json',
                }
            })

            if (response.status !== 200) {
                throw new Error('Erro ao remover do carrinho');
            }

            button.closest('tr').remove();

            Swal.fire('Removido!', 'Produto removido do carrinho.', 'success').then(() => window.location.reload());
        } catch (err) {
            Swal.fire('Erro', err.message, 'error');
        }
    }

    async function changeQuantity(route, uuid, quantity) {
        try {
            const response = await axios.put(route, {
                product_uuid: uuid,
                quantity: quantity,
            }, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })

            if (response.status !== 200) {
                throw new Error(err?.message || 'Erro ao alterar a quantidade do produto');
            }

            Swal.fire('Alterado!', 'Quantidade alterada com sucesso.', 'success').then(() => window.location.reload());
        } catch (err) {
            Swal.fire('Erro', err.message, 'error');
        }
    }
</script>

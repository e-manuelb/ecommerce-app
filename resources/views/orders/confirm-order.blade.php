@php use App\Utils\CurrencyUtil; @endphp
@extends('layouts.app')

@section('content')
    <div class="container p-3 bg-body-tertiary">
        <p class="h3 mb-3">Finalizar pedido</p>
        <div class="mb-3">
            <div class="card">
                <div class="card-body">
                    <table class="table .table-borderless align-middle">
                        <thead>
                        <tr>
                            <th scope="col">Produto</th>
                            <th scope="col">Qtd.</th>
                            <th scope="col">Preço</th>
                            <th scope="col">Total</th>
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
                                            <p class="text">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Quantidade">
                                        {{ $item['quantity'] }}
                                    </div>
                                </td>
                                <td>{{ CurrencyUtil::formatBRL($product->price) }}</td>
                                <td>{{ CurrencyUtil::formatBRL($product->price * $item['quantity']) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Endereço</h6>
                        <form>
                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="form-label" for="cep">CEP</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Digite seu CEP (somente números)"
                                        id="cep"
                                        maxlength="8"
                                        required
                                        pattern="[0-9]{8}"
                                        title="Use apenas 8 dígitos numéricos"
                                        onblur="consultZipCode()"
                                    />
                                </div>
                                <div class="col-4">
                                    <label class="form-label" for="address">Rua (ou avenida)</label>
                                    <input class="form-control" type="text" id="address" required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label" for="neighborhood">Bairro</label>
                                    <input class="form-control" type="text" id="neighborhood" required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label" for="number">Número</label>
                                    <input class="form-control" type="text" id="number" required maxlength="10">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label" for="city">Cidade</label>
                                    <input class="form-control" type="text" id="city" required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label" for="state">UF</label>
                                    <input class="form-control" type="text" id="state" required maxlength="2">
                                </div>
                                <div class="col-4">
                                    <label class="form-label" for="complement">Complemento</label>
                                    <input class="form-control" type="text" id="complement">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-success mt-3" id="checkout-button" onclick="confirmOrder('{{ route('orders.store') }}', '{{ route('products.index') }}')" disabled>
                        Finalizar compra
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    async function consultZipCode() {
        const cepInput = document.getElementById('cep');
        const addressInput = document.getElementById('address');
        const neighborhoodInput = document.getElementById('neighborhood');
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');
        const complementInput = document.getElementById('complement');
        const checkoutButton = document.getElementById('checkout-button');

        cepInput.setCustomValidity('');
        checkoutButton.disabled = true;

        const cep = cepInput.value.trim();

        if (!/^[0-9]{8}$/.test(cep)) {
            cepInput.setCustomValidity('Informe 8 dígitos numéricos. Ex: 61814004');
            cepInput.reportValidity();
            return;
        }

        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);

            if (!response.ok) throw new Error('Erro na requisição');

            const data = await response.json();

            if (data.erro) throw new Error('CEP não encontrado');

            addressInput.value = data.logradouro || '';
            neighborhoodInput.value = data.bairro || '';
            cityInput.value = data.localidade || '';
            stateInput.value = data.uf || '';
            complementInput.value = data.complemento || '';

            checkoutButton.disabled = false;

        } catch (err) {
            Swal.fire('Erro', err.message, 'error');
        }
    }

    async function confirmOrder(route, redirectRoute) {
        const cepInput = document.getElementById('cep');
        const addressInput = document.getElementById('address');
        const neighborhoodInput = document.getElementById('neighborhood');
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');
        const complementInput = document.getElementById('complement');
        const numberInput = document.getElementById('number');

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(route, {
                method: 'POST',
                headers: { 'accept': 'application/json', 'content-type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({
                    address: {
                        zip_code: cepInput.value,
                        street: addressInput.value,
                        number: numberInput.value,
                        complement: complementInput.value,
                        neighborhood: neighborhoodInput.value ?? '',
                        city: cityInput.value,
                        state: stateInput.value
                    },
                })
            });

            if (!response.ok) throw new Error('Erro na requisição');

            if (response.status === 200) {
                Swal.fire('Sucesso', 'Pedido criado com sucesso', 'success').then(() => window.location.href = redirectRoute);
            }
        } catch (err) {
            Swal.fire('Erro', err.message, 'error');
        }
    }
</script>

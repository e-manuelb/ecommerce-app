@php use App\Utils\CurrencyUtil; @endphp
@extends('layouts.app')

@section('content')
    <div class="container p-3 bg-body-tertiary">
        <p class="h3 mb-3">Finalizar pedido</p>

        {{-- Tabela de Produtos --}}
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

        {{-- Endereço --}}
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Endereço</h6>
                <form>
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="form-label" for="cep">CEP</label>
                            <input class="form-control" type="text" placeholder="Digite seu CEP (somente números)" id="cep"
                                   maxlength="8" required pattern="[0-9]{8}" title="Use apenas 8 dígitos numéricos"
                                   onblur="consultZipCode()" />
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

        {{-- Cupons e Total --}}
        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Cupons</p>
                        <p class="card-subtitle text-muted">Adicione cupons de desconto às suas compras</p>
                        <div class="form-group mt-3">
                            <input class="form-control" type="text" id="coupon" placeholder="Digite o cupom">
                            <div id="coupon-feedback" class="form-text mt-1" style="display: none;"></div>
                            <div id="applied-coupons" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary" onclick="validateCoupon()">Validar</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total --}}
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Resumo</p>
                        <p class="card-subtitle text-muted">Subtotal: <span id="subtotal-value">{{ CurrencyUtil::formatBRL($total) }}</span></p>
                        <div id="discount-container" style="display: none;">
                            <p class="card-subtitle text-muted">Descontos: <span id="discount-value">{{ CurrencyUtil::formatBRL(0) }}</span></p>
                        </div>
                        <div class="mt-3">
                            <button
                                type="button"
                                class="btn btn-success"
                                onclick="confirmOrder('{{ route('orders.store') }}', '{{ route('products.index') }}')"
                                id="confirm-order-btn"
                                disabled
                            >
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    const subtotal = <?php echo $total ?>;
    let appliedCoupons = [];
    let appliedDiscounts = [];

    async function validateCoupon() {
        const couponInput = document.getElementById('coupon');
        const feedback = document.getElementById('coupon-feedback');
        const appliedContainer = document.getElementById('applied-coupons');

        const code = couponInput.value.trim().toUpperCase();
        if (!code) return;

        if (appliedCoupons.includes(code)) {
            feedback.textContent = `O cupom "${code}" já foi adicionado.`;
            feedback.style.display = 'block';
            feedback.classList.remove('text-success');
            feedback.classList.add('text-danger');
            return;
        }

        const route = '{{ route('coupons.validate') }}';

        try {
            const response = await axios.post(route, { code }, {
                headers: { 'Content-Type': 'application/json' },
            });

            const data = response.data.data;

            feedback.textContent = `Cupom "${data.code}" aplicado com sucesso!`;
            feedback.style.display = 'block';
            feedback.classList.remove('text-danger');
            feedback.classList.add('text-success');

            appliedCoupons.push(data.code.toUpperCase());

            let discount = 0;
            if (data.discount_type === 'PERCENTAGE') {
                discount = subtotal * (data.discount / 100);
            } else {
                discount = data.discount;
            }
            appliedDiscounts.push(discount);

            const badge = document.createElement('span');
            badge.className = 'badge bg-success text-white';
            badge.textContent = data.code.toUpperCase();
            appliedContainer.appendChild(badge);

            const totalDiscount = appliedDiscounts.reduce((acc, val) => acc + val, 0);
            document.getElementById('discount-value').textContent = formatCurrency(totalDiscount);
            document.getElementById('discount-container').style.display = 'block';

            couponInput.value = '';
        } catch (err) {
            feedback.textContent = 'Cupom inválido.';
            feedback.style.display = 'block';
            feedback.classList.remove('text-success');
            feedback.classList.add('text-danger');
        }
    }

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL',
            minimumFractionDigits: 2
        });
    }

    async function consultZipCode() {
        const cepInput = document.getElementById('cep');
        const addressInput = document.getElementById('address');
        const neighborhoodInput = document.getElementById('neighborhood');
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');
        const complementInput = document.getElementById('complement');
        const confirmOrderButton = document.getElementById('confirm-order-btn');

        cepInput.setCustomValidity('');
        if (confirmOrderButton) confirmOrderButton.disabled = true;

        const cep = cepInput.value.trim();

        if (!/^[0-9]{8}$/.test(cep)) {
            cepInput.setCustomValidity('Informe 8 dígitos numéricos. Ex: 61814004');
            cepInput.reportValidity();
            return;
        }

        try {
            const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
            const data = response.data;

            if (data.erro) throw new Error('CEP não encontrado');

            addressInput.value = data.logradouro || '';
            neighborhoodInput.value = data.bairro || '';
            cityInput.value = data.localidade || '';
            stateInput.value = data.uf || '';
            complementInput.value = data.complemento || '';

            if (confirmOrderButton) confirmOrderButton.disabled = false;
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
            const response = await axios.post(route, {
                coupons: appliedCoupons,
                address: {
                    zip_code: cepInput.value,
                    street: addressInput.value,
                    number: numberInput.value,
                    complement: complementInput.value,
                    neighborhood: neighborhoodInput.value ?? '',
                    city: cityInput.value,
                    state: stateInput.value
                }
            }, {
                headers: { 'Content-Type': 'application/json' },
            });

            if (response.status === 201) {
                Swal.fire('Sucesso', 'Pedido criado com sucesso', 'success')
                    .then(() => window.location.href = redirectRoute);
            } else {
                throw new Error('Erro na requisição');
            }
        } catch (err) {
            Swal.fire('Erro', err.message, 'error');
        }
    }
</script>

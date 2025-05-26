@php
    use App\Models\ProductVariation;
@endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-3 bg-body-tertiary">
        <div>
            <p class="h3">Variações de Produtos</p>
        </div>
        <div class="text-end mb-3">
            <a href="{{ route('product-variations.create') }}" class="btn btn-success">Criar</a>
        </div>
        @if(!$productVariations || count($productVariations) == 0)
            <div class="text-center align-middle p-3">
                <p class="h4">Não há Produtos disponíveis.</p>
            </div>
        @else
            <div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">SKU</th>
                        <th scope="col">Produto Original</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php /** @var ProductVariation[] $productVariations */ ?>
                    @foreach($productVariations as $productVariation)
                        <tr>
                            <td>{{ $productVariation->sku }}</td>
                            <td>{{ "$productVariation->product_id - {$productVariation->product->name}" }}</td>
                            <td>{{ $productVariation->name }}</td>
                            <td>{{ $productVariation->description }}</td>
                            <td>{{ (new NumberFormatter('pt_BR',  NumberFormatter::CURRENCY))->format($productVariation->price, NumberFormatter::TYPE_DEFAULT) }}</td>
                            <td>{{ !!$productVariation->stock ? $productVariation->stock->quantity : ''}}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a class="btn btn-outline-secondary"
                                       href="{{ route('product-variations.edit', $productVariation->uuid) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <a class="btn btn-outline-secondary"
                                       href="{{ route('product-variations.show', $productVariation->uuid) }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="deleteProduct('{{ route('product-variations.destroy', $productVariation->uuid) }}')"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $productVariations->links() }}
            </div>
        @endif
    </div>
@endsection
<script>
    function deleteProduct(route) {
        console.log(route);
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await axios.delete(route, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 200) {
                    Swal.fire('Removido!', 'Produto removido com sucesso.', 'success').then(() => window.location.reload());

                    return;
                }

                Swal.fire('Erro', 'Produto não pôde ser removido.', 'error').then(() => window.location.reload());
            }
        });
    }
</script>

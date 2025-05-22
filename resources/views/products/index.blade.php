@php use App\Models\Product;use App\Utils\CurrencyUtil;use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
    <div class="container border p-3 bg-body-tertiary">
        <div>
            <p class="h3">Produtos</p>
        </div>
        <div class="text-end mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-success">Criar</a>
        </div>
        <div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">SKU</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var Product[] $products */ ?>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ Str::limit($product->description, 30) }}</td>
                        <td>{{ CurrencyUtil::formatBRL($product->price) }}</td>
                        <td>{{ !!$product->stock ? $product->stock->quantity : 0}}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn btn-outline-secondary" href="{{ route('products.edit', $product->uuid) }}"><i class="bi bi-pencil"></i></a>
                                <a class="btn btn-outline-secondary" href="{{ route('products.show', $product->uuid) }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-outline-secondary delete-product-button"
                                        data-id="{{ $product->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <form id="delete-form-{{ $product->id }}"
                          action="{{ route('products.destroy', $product->uuid) }}"
                          method="POST"
                          style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-product-button').forEach(button => {
            button.addEventListener('click', function (e) {
                const id = button.getAttribute('data-id');
                const form = document.getElementById('delete-form-' + id);

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, excluir!',
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


@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Devolução de Produtos</h4>
        </div>
        <div class="card-body">
            <!-- Formulário de busca -->
            <form method="GET" action="{{ route('devolucoes.devolucao') }}" class="row g-3 mb-4">
                <div class="col-md-9">
                    <input type="text" name="codigo_fatura" class="form-control"
                           placeholder="Digite o código da fatura" value="{{ $codigo_fatura }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        Buscar
                    </button>
                </div>
            </form>

            @if(count($vendas))
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th>Qtd Vendida</th>
                                <th>Preço Unitário</th>
                                <th>Subtotal</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendas as $venda)
                                <tr>
                                    <td>{{ $venda->produto->nome }}</td>
                                    <td>{{ $venda->quantidade }}</td>
                                    <td>KZ {{ number_format($venda->preco_unitario, 2) }}</td>
                                    <td>KZ {{ number_format($venda->subtotal, 2) }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('devolucoes.eliminar', $venda->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Deseja realmente devolver este produto?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Devolver
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning mt-3" role="alert">
                    Nenhuma venda encontrada para este código de fatura.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

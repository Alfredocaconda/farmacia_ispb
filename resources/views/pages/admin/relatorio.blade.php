@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Relatório de Vendas</h2>
        <form method="GET" action="{{ route('vendas.relatorio') }}" class="mb-4">
            <div class="row">
                <div class="col">
                    <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                </div>
                <div class="col">
                    <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                </div>
                <div class="col">
                    <input type="text" name="pesquisa" class="form-control" placeholder="Nome do funcionário ou produto" value="{{ request('pesquisa') }}">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Funcionário:</strong> {{ $vendas->first()->funcionario->nome ?? 'Funcionário não encontrado' }}
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Data da Venda</th>
                        <th>Nome do Funcionário</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendas as $venda)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y') }}</td>
                            <td>{{ $venda->funcionario->nome ?? 'Funcionário não encontrado' }}</td>
                            <td>
                                {{ $venda->produto->nome ?? 'Produto não encontrado' }}
                                / {{ $venda->produto->descricao ?? '' }}
                                / {{ $venda->produto->categoria ?? '' }}
                            </td>
                            <td>{{ $venda->quantidade }}</td>
                            <td>{{ number_format($venda->preco_unitario, 2, ',', '.') }}</td>
                            <td>{{ number_format($venda->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma venda encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total Geral:</strong></td>
                        <td><strong>{{ number_format($totalGeral, 2, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

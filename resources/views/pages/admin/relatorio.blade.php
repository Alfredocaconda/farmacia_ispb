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
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    @foreach($vendasAgrupadas as $codigo => $vendas)
        <div class="card mb-3">
            <div class="card-header">
                <strong>Código da Venda:</strong> {{ $codigo }} |
                <strong>Data:</strong> {{ \Carbon\Carbon::parse($vendas->first()->data_venda)->format('d/m/Y H:i') }} |
                <strong>Funcionário:</strong> {{ $vendas->first()->funcionario->nome ?? 'Funcionário não encontrado' }}
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendas as $venda)
                            <tr>
                                <td>{{ $venda->produto->nome ?? 'Produto não encontrado' }}</td>
                                <td>{{ $venda->quantidade }}</td>
                                <td>{{ number_format($venda->preco_unitario, 2, ',', '.') }}</td>
                                <td>{{ number_format($venda->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total da Venda:</strong></td>
                            <td><strong>{{ number_format($vendas->sum('subtotal'), 2, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endforeach

    <div class="alert alert-success">
        <strong>Total Geral:</strong> {{ number_format($totalGeral, 2, ',', '.') }}
    </div>
</div>
@endsection

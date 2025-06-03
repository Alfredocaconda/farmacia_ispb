<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Vendas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, p { text-align: center; }
    </style>
</head>
<body>
    <h2>Relatório de Vendas</h2>
    <p>
        @if($dataInicio && $dataFim)
            De {{ $dataInicio }} até {{ $dataFim }}
        @endif
        @if($pesquisa)
            — Filtro: "{{ $pesquisa }}"
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Funcionário</th>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendas as $venda)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($venda->data_venda)->format('Y-m-d') }}</td>
                    <td>{{ $venda->funcionario->nome ?? 'N/D' }}</td>
                    <td>{{ $venda->produto->nome ?? 'N/D' }}</td>
                    <td>{{ $venda->quantidade }}</td>
                    <td>{{ number_format($venda->preco_unitario, 2, ',', '.') }}</td>
                    <td>{{ number_format($venda->subtotal, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Nenhuma venda encontrada.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total Geral:</strong></td>
                <td><strong>{{ number_format($totalGeral, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

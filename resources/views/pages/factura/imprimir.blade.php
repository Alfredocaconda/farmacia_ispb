<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recibo - {{ $vendas[0]->codigo_fatura }}</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 280px;
            margin: auto;
        }
        .center { text-align: center; }
        .border-top { border-top: 1px dashed #000; margin-top: 10px; }
        .border-bottom { border-bottom: 1px dashed #000; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        .totais td { font-weight: bold; }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <h3>Farmácia Boa Saúde</h3>
        <p>NIF: 123456789</p>
        <p>Av. Principal, Bairro Central</p>
        <p>Telefone: 999-999-999</p>
    </div>

    <div class="border-top border-bottom center">
        <p><strong>RECIBO DE VENDA</strong></p>
        <p>Fatura Nº: {{ $vendas[0]->codigo_fatura }}</p>
        <p>Data: {{ \Carbon\Carbon::parse($vendas[0]->data_venda)->format('d/m/Y H:i') }}</p>
        <p>Funcionário: {{ $funcionario->nome ?? 'Desconhecido' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($vendas as $venda)
                <tr>
                    <td>{{ $venda->produto->nome ?? 'Removido' }}</td>
                    <td>{{ $venda->quantidade }}</td>
                    <td>{{ number_format($venda->subtotal, 2, ',', '.') }}</td>
                </tr>
                @php $total += $venda->subtotal; @endphp
            @endforeach
        </tbody>
    </table>

    <hr>
    <table class="totais">
        <tr>
            <td>Total:</td>
            <td style="text-align: right">{{ number_format($total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="center border-top">
        <p>Obrigado pela preferência!</p>
        <p>Volte sempre!</p>
    </div>

</body>
</html>

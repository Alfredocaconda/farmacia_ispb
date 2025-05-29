<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovativo de Venda</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body onload="window.print()">

    <h2>Farmácia Exemplo</h2>
    <p><strong>Data:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>ID da Venda:</strong> {{ $venda->id }}</p>
    <h2>Comprovativo da Venda: <strong>{{ $codigo_venda }}</strong></h2>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venda->itens as $item)
                <tr>
                    <td>{{ $item->produto->nome }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>{{ number_format($item->preco, 2) }}</td>
                    <td>{{ number_format($item->preco * $item->quantidade, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> {{ number_format($venda->total, 2) }} KZ</p>
    <p>Obrigado pela preferência!</p>

</body>
</html>

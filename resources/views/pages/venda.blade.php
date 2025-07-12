@extends('layouts.base')
@section('title', 'VENDAS')
@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo da Farmácia" style="height: 50px; margin-right: 10px;">
            <h3 class="mb-0">SMILE FARMA & ODONTO-COMERÇIO E SERVIÇOS, LDA</h3>
        </div>
        <div class="text-end">
            <div><strong>Funcionário:</strong> {{ Auth::guard('funcionario')->user()->nome ?? 'Desconhecido' }}</div>
            <div><strong>Função:</strong> {{ Auth::guard('funcionario')->user()->funcao ?? '---' }}</div>
            @php $funcao = Auth::guard('funcionario')->user()->funcao ?? null; @endphp
            @if ($funcao === 'Gerente')
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-danger">SAIR</a>
            @else
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger"> <i class="fa fa-sign-out-alt"></i> SAIR</button>
                </form>
            @endif
        </div>
    </div>

    @if (session('SUCESSO'))
        <div class="alert alert-green">{{ session('SUCESSO') }}</div>
    @endif
    @if (session('ERRO'))
        <div class="alert alert-danger">{{ session('ERRO') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="row justify-content-center mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('vendas.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Pesquisar produto..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Pesquisar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tabela-rolavel">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr><th>Produto</th><th>Preço</th><th>Qtd em Stock</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        @foreach($stocks as $stock)
                        @php
                            $caducado = \Carbon\Carbon::parse($stock->caducidade)->isPast();
                            if ($caducado) continue;
                            $baixo_stock = $stock->qtd_stock < 10;
                        @endphp
                        <tr class="{{ ($baixo_stock ? 'linha-baixa-verde' : '') }}">
                            <td>{{ $stock->produto->nome }}</td>
                            <td>{{ number_format($stock->preco, 2) }} Kz</td>
                            <td>{{ $stock->qtd_stock }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-selecionar" data-stock-id="{{ $stock->id }}" data-produto-nome="{{ $stock->produto->nome }}" data-max-qtd="{{ $stock->qtd_stock }}">Selecionar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <h4>Carrinho</h4>
            @php $total = 0; @endphp
            @if(empty($cart))
                <p class="text-muted">Sem produtos no carrinho.</p>
            @else
                @php foreach($cart as $item) { $subtotal = $item['preco'] * $item['quantidade']; $total += $subtotal; } @endphp
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>Produto</th><th>Qtd</th><th>Total</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                        @php $subtotal = $item['preco'] * $item['quantidade']; @endphp
                        <tr>
                            <td>{{ $item['nome'] }}</td>
                            <td>{{ $item['quantidade'] }}</td>
                            <td>{{ number_format($subtotal, 2) }} Kz</td>
                            <td><a href="{{ route('vendas.remove', $item['id']) }}" class="btn btn-sm btn-danger">X</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p><strong>Total Geral:</strong> {{ number_format($total, 2) }} Kz</p>
                <form id="formFinalizarVenda" action="{{ route('vendas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_funcionario" value="{{ Auth::guard('funcionario')->user()->id }}">
                    <input type="hidden" id="campoImprimir" name="imprimir" value="nao">
                    <div class="mb-2">
                        <label for="valor_entregue" class="form-label">Valor entregue pelo cliente</label>
                        <input type="number" name="valor_entregue" id="valor_entregue" class="form-control" step="0.01" min="{{ $total }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="troco" class="form-label">Troco</label>
                        <input type="text" id="troco" class="form-control bg-light" readonly>
                    </div>
                    <button type="button" class="btn btn-success w-100 mb-2" id="btnConfirmarVenda">Finalizar Venda</button>
                </form>
                <a href="{{ route('vendas.clear') }}" class="btn btn-secondary w-100">Limpar Carrinho</a>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const valorEntregueInput = document.getElementById('valor_entregue');
                        const trocoInput = document.getElementById('troco');
                        const total = {{ json_encode($total) }};

                        if (valorEntregueInput) {
                            valorEntregueInput.addEventListener('input', function () {
                                const valor = parseFloat(this.value);
                                if (!isNaN(valor)) {
                                    const troco = valor - total;
                                    trocoInput.value = troco >= 0 ? troco.toFixed(2) + ' Kz' : 'Valor insuficiente';
                                } else {
                                    trocoInput.value = '';
                                }
                            });
                        }
                    });
                </script>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.createElement('div');
        modal.id = 'customModal';
        modal.style = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center;';
        modal.innerHTML = `
            <div style="background: white; padding: 20px; border-radius: 10px; width: 300px; max-width: 90%;">
                <h5 id="productName"></h5>
                <form id="modalForm" method="POST" action="{{ route('vendas.add') }}">
                    @csrf
                    <input type="hidden" name="stock_id" id="modalStockId">
                    <div class="mb-3">
                        <label for="modalQuantidade" class="form-label">Quantidade</label>
                        <input type="number" name="quantidade" id="modalQuantidade" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Adicionar</button>
                        <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(modal);

        document.querySelectorAll('.btn-selecionar').forEach(button => {
            button.addEventListener('click', function () {
                const stockId = this.dataset.stockId;
                const nomeProduto = this.dataset.produtoNome;
                const maxQtd = this.dataset.maxQtd;

                document.getElementById('modalStockId').value = stockId;
                document.getElementById('productName').textContent = "Adicionar: " + nomeProduto;
                document.getElementById('modalQuantidade').max = maxQtd;
                document.getElementById('modalQuantidade').value = "";
                modal.style.display = 'flex';
            });
        });
    });
    function fecharModal() {
        document.getElementById('customModal').style.display = 'none';
    }
</script>

<div class="modal" tabindex="-1" id="confirmarImpressaoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background: #fff; padding: 20px; border-radius: 10px; width: 400px;">
        <h5>Deseja imprimir o comprovativo?</h5>
        <div class="mt-3 d-flex justify-content-end">
            <button class="btn btn-secondary me-2" onclick="confirmarImpressao(false)">Não</button>
            <button class="btn btn-primary" onclick="confirmarImpressao(true)">Sim</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnConfirmarVenda').addEventListener('click', function () {
        document.getElementById('confirmarImpressaoModal').style.display = 'flex';
    });

    function confirmarImpressao(deveImprimir) {
        document.getElementById('campoImprimir').value = deveImprimir ? 'sim' : 'nao';
        document.getElementById('confirmarImpressaoModal').style.display = 'none';
        document.getElementById('formFinalizarVenda').submit();
    }
</script>

@if(session('codigo_fatura') && session('imprimir') === 'sim')
<script>
    window.open("{{ route('vendas.imprimir', ['codigo_fatura' => session('codigo_fatura')]) }}", '_blank');
</script>
@endif
@endsection

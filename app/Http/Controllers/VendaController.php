<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class VendaController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->input('search');

        $stocks = Stock::with('produto')
            ->where('qtd_stock', '>', 0)
            ->when($search, function ($query, $search) {
                return $query->whereHas('produto', function ($q) use ($search) {
                    $q->where('nome', 'like', '%' . $search . '%');
                });
            })
            ->get();

        $cart = Session::get('cart', []);

        return view('pages.venda', compact('stocks', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        $stock = Stock::with('produto')->findOrFail($request->stock_id);
        $id = $stock->id;
        $quantidade = $request->quantidade;

        if ($quantidade > $stock->qtd_stock) {
            return back()->with('ERRO', 'Quantidade solicitada é maior que o estoque.');
        }

        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantidade'] += $quantidade;
        } else {
            $cart[$id] = [
                'id' => $id,
                'nome' => $stock->produto->nome,
                'preco' => $stock->preco,
                'quantidade' => $quantidade,
            ];
        }

        Session::put('cart', $cart);
        return back();
    }

    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);
        return back();
    }

    public function clearCart()
    {
        Session::forget('cart');
        return back();
    }

   public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('ERRO', 'O carrinho está vazio.');
            }

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['preco'] * $item['quantidade'];
            }

            $valor_entregue = $request->valor_entregue;

            if ($valor_entregue < $total) {
                return redirect()->back()->with('ERRO', 'O valor entregue é menor que o total da compra.');
            }

            $troco = $valor_entregue - $total;

            // Gerar um código único para a venda
            $codigoVenda = 'VENDA-' . strtoupper(uniqid());

        try {
            foreach ($cart as $item) {
                $subtotal = $item['preco'] * $item['quantidade'];

                // Registra a venda
                $venda = new Venda();
                $venda->codigo_venda = $codigoVenda;
                $venda->produto_id = $item['id']; // ou stock_id, se for o caso
                $venda->quantidade = $item['quantidade'];
                $venda->preco_unitario = $item['preco'];
                $venda->subtotal = $subtotal;
                $venda->data_venda = now();
                $venda->funcionario_id=$request->id_funcionario;// se tiver controle de usuário
                $venda->save();

                // Atualiza o estoque
                $stock = Stock::find($item['id']);
                $stock->qtd_stock -= $item['quantidade'];
                $stock->save();
                }

            session()->forget('cart');

            return redirect()->route('vendas.index');

        } catch (\Exception $e) {
            return redirect()->back()->with('ERRO', 'Erro ao finalizar venda: ' . $e->getMessage());
        }
    }


    public function comprovativo($codigo_venda)
    {
        // Busca todas as linhas da venda pelo código
        $vendas = Venda::where('codigo_venda', $codigo_venda)->with('produto')->get();

        if ($vendas->isEmpty()) {
            return redirect()->route('vendas.index')->with('ERRO', 'Venda não encontrada.');
        }

        return view('pages.comprovativo', compact('vendas', 'codigo_venda'));
    }

    public function relatorio(Request $request)
    {
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');
        $pesquisa = $request->input('pesquisa');

        // Se nada for inserido, retorna uma coleção vazia
        if (!$dataInicio && !$dataFim && !$pesquisa) {
            $vendas = collect();
            $totalGeral = 0;
        } else {
            $query = Venda::with(['produto', 'funcionario']);

            // Filtro por data (sem hora)
            if ($dataInicio && $dataFim) {
                $query->whereBetween('data_venda', [$dataInicio, $dataFim]);
            }

            // Se houver texto pesquisado (produto ou funcionário)
            if ($pesquisa) {
                $query->where(function ($q) use ($pesquisa) {
                    $q->whereHas('funcionario', function ($q2) use ($pesquisa) {
                        $q2->where('nome', 'like', '%' . $pesquisa . '%');
                    })->orWhereHas('produto', function ($q3) use ($pesquisa) {
                        $q3->where('nome', 'like', '%' . $pesquisa . '%');
                    });
                });
            }

            $vendas = $query->orderBy('data_venda', 'desc')->get();
            $totalGeral = $vendas->sum('subtotal');
        }

        return view('pages.admin.relatorio', compact('vendas', 'totalGeral', 'dataInicio', 'dataFim', 'pesquisa'));
    }

}

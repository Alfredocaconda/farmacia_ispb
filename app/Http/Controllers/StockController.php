<?php

namespace App\Http\Controllers;

use App\Models\stock;
use App\Models\produto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index($id = null){
            if ($id) {
                // Buscar um único produto e seus stocks
                $valor = produto::orderby('nome','desc')->findOrFail($id);
                $stock = stock::with('produto', 'funcionario')->where('id_produto', $id)->get();
            } else {
                // Buscar todos os produtos e todos os stocks
                $valor = null;
                $stock = stock::with('produto', 'funcionario')->get();
            }
                // Verificar dias restantes para validade
                foreach ($stock as $item) {

                    if ($item->caducidade) {
                        $dias = Carbon::now()->diffInDays($item->caducidade, false);

                        if ($dias <= 0) {
                            $item->alerta = 'expirado';
                        } elseif ($dias <= 5) {
                            $item->alerta = 'critico';
                        } elseif ($dias <= 10) {
                            $item->alerta = 'atencao';
                        } else {
                            $item->alerta = 'normal';
                        }
                    } else {
                        $item->alerta = 'sem_validade';
                    }
                }
            return view('pages.admin.stocks', compact('stock', 'valor'));
        }
       /**
     * Remove the specified resource from storage.
     */
    public function view($id){
        $valor=produto::findOrFail($id);
      return view('pages.admin.stocks',compact('valor'));  
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
          
             $rules = [
                'fornecedor' => ['required', 'string', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
                'preco' => ['required'],
                'codigo_barra' => ['required'],
                'qtd_stock' => ['required'],
                'caducidade' => ['required'],
            ];

            $request->validate($rules, [
                'fornecedor.required' => 'O fornecedor É OBRIGATÓRIO!',
                'fornecedor.regex' => 'O NOME DO FORNECEDOR DEVE CONTER APENAS LETRAS!',
                'codigo_barra.required' => 'CODIGO DE BARRA É OBRIGATÓRIA!',
                'qtd_stock.required' => 'QUANTIDADE É OBRIGATÓRIA!',
                'caducidade.required' => 'CADUCIDADE É OBRIGATÓRIA!',
                'preco.required' => 'PREÇO É OBRIGATÓRIA!',
            ]);

              // Se for para aumentar a quantidade
        if ($request->filled('id') && $request->tipo === 'aumentar') {
            $stock = Stock::find($request->id);
            if (!$stock) {
                return redirect()->route('stock.index')->with("ERRO", "Stock não encontrado.");
            }

            $stock->qtd_stock += $request->qtd_stock;
            $stock->save();

            return redirect()->route('stock.index')->with("SUCESSO", "QUANTIDADE AUMENTADA COM SUCESSO");
        }
        if ($request->filled('id')){
             // EDIÇÃO NORMAL (atualiza tudo, inclusive quantidade)
            $valor = Stock::find($request->id);
            if (!$valor) {
                return redirect()->route('stock.index')->with("ERRO", "Stock não encontrado.");
            }
                $valor->id_funcionario =$request->id_funcionario;
                $valor->id_produto = $request->id_produto;
                $valor->preco = $request->preco;
                $valor->fornecedor = $request->fornecedor;
                $valor->codigo_barra = $request->codigo_barra;
                $valor->qtd_stock = $request->qtd_stock;
                $valor->caducidade = $request->caducidade;
                $valor->data_entrada = now();
                $valor->save();

                return redirect()->route('stock.index')->with("SUCESSO", "STOCK ATUALIZADO COM SUCESSO");
            } else {
                // Nova entrada: Verificar se o produto já tem stock
                $valor = Stock::where('id_produto', $request->id_produto)->first();

                if ($valor) {
                    // Produto já tem stock, aumentar a quantidade
                    $valor->qtd_stock += $request->qtd_stock;
                    $valor->preco = $request->preco;
                    $valor->caducidade = $request->caducidade;
                    $valor->fornecedor = $request->fornecedor;
                    $valor->codigo_barra = $request->codigo_barra;
                    $valor->data_entrada = now();
                    $valor->id_funcionario =$request->id_funcionario;
                    $valor->save();

                    return redirect()->route('stock.index')->with("SUCESSO", "QUANTIDADE ATUALIZADA NO STOCK");
                } else {
                    // Produto novo no stock
                    $valor = new Stock();
                    $valor->id_funcionario =$request->id_funcionario;
                    $valor->id_produto = $request->id_produto;
                    $valor->preco = $request->preco;
                    $valor->fornecedor = $request->fornecedor;
                    $valor->codigo_barra = $request->codigo_barra;
                    $valor->qtd_stock = $request->qtd_stock;
                    $valor->caducidade = $request->caducidade;
                    $valor->data_entrada = now();
                    $valor->save();

                    return redirect()->route('stock.index')->with("SUCESSO", "STOCK CADASTRADO COM SUCESSO");
                }
            }
        } catch (ValidationException $e) {
            return redirect()->route('stock.index')->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            return redirect()->route('stock.index')->with("ERRO", "ERRO AO CADASTRAR O STOCK");
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    $valor=stock::find($id);
    if (!$valor) {
        # code...
        return redirect()->back()->with("ERRO","FUNCIONÁRIO NÃO ENCONTRADO");
    } 
    return view('pages.admin.stocks',compact('valor'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $valor=stock::find($id);
        if(!$valor){
            return redirect()->back()->with("ERRO","STOCK NÃO ENCONTRADO");
        }
        $valor->delete();
        return redirect()->back()->with("SUCESSO","STOCK APAGADO COM SUCESSO");
    } 
}

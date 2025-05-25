<?php

namespace App\Http\Controllers;

use App\Models\stock;
use App\Models\produto;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index($id = null)
{
    if ($id) {
        // Buscar um único produto e seus stocks
        $valor = produto::findOrFail($id);
        $stock = stock::with('produto', 'funcionario')->where('id_produto', $id)->get();
    } else {
        // Buscar todos os produtos e todos os stocks
        $valor = null;
        $stock = stock::with('produto', 'funcionario')->get();
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
        //
        try {
                     
            if ($request->filled('id')) {
                # code...
                $valor=stock::find($request->id);
            } else {
                # code...
                $valor=new stock();
            }
            //PREENCHER OS CAMPOS DO FORMULARIO
            //$valor->id_funcionario=Auth::funcionario();
            $valor->id_funcionario=1;
            $valor->id_produto=$request->id_produto;
            $valor->preco=$request->preco;
            $valor->qtd_stock=$request->qtd_stock;
            $valor->caducidade=$request->caducidade;
            $valor->data_entrada=now();
            $valor->save();
            
            // Redirecionar para a listagem completa (sem ID)
            return redirect()->route('stock.index')->with("SUCESSO","STOCK CADASTRADO COM SUCESSO");
        } catch (validactionException $e) {
            //throw $th;
            return redirect()->route('stock.index')->witherror($e->validator)->withInput();
        } catch(QueryExecption $e){
            return redirect()->route('stock.index')->with("ERRO","ERRO AO CADASTRAR O STOCK");

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

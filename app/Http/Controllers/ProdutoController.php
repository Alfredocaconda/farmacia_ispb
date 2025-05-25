<?php

namespace App\Http\Controllers;

use App\Models\produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $valor=produto::orderby('nome','desc','funcionario')->get();
        return view('pages.admin.produto',compact('valor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        //
        try {
            //DEFINIR REGRAS DE VALIDAÇÃO
            $rules=[
            'nome' => ['required', 'string','regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
            'descricao' => ['required'],
            'categoria' => ['required'],
            ];
            //VALIDAÇÃO DOS CAMPOS
            $request->validate($rules,[
                'nome'=>'O NOME É OBRIGATÓRIO!',
                'descricao'=>'O DESCRIÇÃO É OBRIGATÓRIO!',
                'categoria'=>'O CATEGORIA É OBRIGATÓRIO!',
            ]);

            //CODIGO PARA VERIFICAR SE É EDIÇÃO OU NOVO CADASTRO
            if ($request->filled('id')) {
                # code...
                $valor=produto::find($request->id);
            } else {
                # code...
                $valor=new produto();
            }
            //PREENCHER OS CAMPOS DO FORMULARIO
            $valor->nome=$request->nome;
            $valor->descricao=$request->descricao;
            $valor->categoria=$request->categoria;
            $valor->id_funcionario=1;
            $valor->save();
            return redirect()->back()->with("SUCESSO",$request->filled('id') ? "PRODUTO ACTUALIZADO COM SUCESSO" : "PRODUTO CADASTRADO COM SUCESSO");

        } catch (validectionException $e) {
            //throw $th;
            return redirect()->back()->withErros($e->validator)->withInput();
        } catch(QueryException $e){
            return redirect()->back()->with("ERROR","ERRO AO SALVAR PRODUTO. TENTE NOVAMENTE");
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    $valor=produto::find($id);
    if (!$valor) {
        # code...
        return redirect()->back()->with("ERRO","FUNCIONÁRIO NÃO ENCONTRADO");
    } 
    return view('pages.admin.produto',compact('valor'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $valor=produto::find($id);
        if(!$valor){
            return redirect()->back()->with("ERRO","PRODUTO NÃO ENCONTRADO");
        }
        $valor->delete();
        return redirect()->back()->with("SUCESSO","PRODUTO APAGADO COM SUCESSO");
    }
}

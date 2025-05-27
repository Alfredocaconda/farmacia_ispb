<?php

namespace App\Http\Controllers;

use App\Models\funcionario;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $funcionario=funcionario::orderby('nome','asc')->get();
        return view('pages.admin.funcionario',compact('funcionario'));
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
            'nome' => ['required', 'string', 'min:10', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
            'email'=>'required|email|unique:funcionarios,email',
            'telefone'=>'required|digits:9|unique:funcionarios,telefone'];
            //SE FOR EDIÇÃO ELA DESCONSIDERA O UNIQUE PARA O FUNCIONARIO
            if($request->filled('id')){
                $funcionarioExistente= funcionario::find($request->id);
                if(!$funcionarioExistente){
                    return redirect()->back()->with("ERRO","FUNCIONARIO NÃO ENCONTRADO");
                }
                $rules['email']='required|email|unique:funcionarios,email,'.$request->id;
                $rules['telefone']='required|digits:9|unique:funcionarios,telefone,'.$request->id;
            }
            //VALIDAÇÃO DOS CAMPOS
            $request->validate($rules,[
                'nome'=>'O NOME É OBRIGATÓRIO!',
                'nome'=>'O NOME DEVE TER PELO MENOS 10 LETRAS',
                'email.required' => 'O EMAIL É OBRIGATÓRIO!',
                'email.email' => 'INFORME UM EMAIL VÁLIDO!',
                'email.unique' => 'ESTE EMAIL JÁ ESTA ACADASTRADO!',
                'telefone.required' => 'O TELEFONE É OBRIGATÓRIO!',
                'telefone.unique' => 'ESTE TELEFONE JÁ ESTA CADASTRADO!',
                'telefone.digits' => 'O TELEFONE DEVE TER EXATAMENTE 9 DÍGITOS!',
            ]);

            //CODIGO PARA VERIFICAR SE É EDIÇÃO OU NOVO CADASTRO
            if ($request->filled('id')) {
                # code...
                $valor=funcionario::find($request->id);
            } else {
                # code...
                $valor=new funcionario();
            }
            //PREENCHER OS CAMPOS DO FORMULARIO
            $valor->nome=$request->nome;
            $valor->telefone=$request->telefone;
            $valor->endereco=$request->endereco;
            $valor->email=$request->email;
            $valor->data_contrato=$request->data_contrato;
            $valor->n_bilhete=$request->n_bilhete;
            $valor->senha=$request->senha;
            $valor->funcao=$request->funcao;
            $valor->save();
            return redirect()->back()->with("SUCESSO",$request->filled('id') ? "FUNCIONARIO ACTUALIZADO COM SUCESSO" : "FUNCIONARIO CADASTRADO COM SUCESSO");

        } catch (validectionException $e) {
            //throw $th;
            return redirect()->back()->withErros($e->validator)->withInput();
        } catch(QueryException $e){
            return redirect()->back()->with("ERROR","ERRO AO SALVAR FUNCIONARIO. TENTE NOVAMENTE");
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $funcionario=funcionario::find($id);
        if(!$funcionario){
            return redirect()->back()->with("ERRO","FUNCIONÁRIO NÃO ENCONTRADO!");
        }
        return view('pages.admin.funcionario',compact('funcionario'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $funcionario=funcionario::find($id);
        if (!$funcionario) {
            # code...
            return redirect()->back()->with("ERRO","FUNCIONÁRIO NÃO ENCONTRADO.");
        } 
        $funcionario->delete();
        return redirect()->back()->with("SUCESSO","FUNCIONÁRIO APAGADO COM SUCESSO");
    }
}

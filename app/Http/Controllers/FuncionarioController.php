<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     // Lista funcionários
    public function index()
    {
        $funcionario = Funcionario::orderBy('nome', 'asc')->get();
        return view('pages.admin.funcionario', compact('funcionario'));
    }
    /**
     * Display a listing of the resource.
     */
    // Cria ou atualiza funcionário
    public function store(Request $request)
    {
        try {
            // Regras de validação
            $rules = [
                'nome' => ['required', 'string', 'min:10', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
                'email' => 'required|email|unique:funcionarios,email',
                'telefone' => 'required|digits:9|unique:funcionarios,telefone'
            ];

            // Ajusta regras para edição
            if ($request->filled('id')) {
                $funcionarioExistente = Funcionario::find($request->id);
                if (!$funcionarioExistente) {
                    return redirect()->back()->with("ERRO", "FUNCIONÁRIO NÃO ENCONTRADO");
                }

                $rules['email'] = 'required|email|unique:funcionarios,email,' . $request->id;
                $rules['telefone'] = 'required|digits:9|unique:funcionarios,telefone,' . $request->id;
            }

            // Validação
            $request->validate($rules);

            // Cria ou edita funcionário
            $valor = $request->filled('id') 
                ? Funcionario::find($request->id) 
                : new Funcionario();

            $valor->nome = $request->nome;
            $valor->telefone = $request->telefone;
            $valor->endereco = $request->endereco;
            $valor->email = $request->email;
            $valor->data_contrato = $request->data_contrato;
            $valor->n_bilhete = $request->n_bilhete;
            $valor->funcao = $request->funcao;
            $valor->senha = Hash::make($request->senha);
            $valor->save();

            return redirect()->back()->with("SUCESSO", $request->filled('id') ? "FUNCIONÁRIO ACTUALIZADO COM SUCESSO" : "FUNCIONÁRIO CADASTRADO COM SUCESSO");

        } catch (QueryException $e) {
            return redirect()->back()->with("ERRO", "ERRO AO SALVAR FUNCIONÁRIO. TENTE NOVAMENTE");
        }
    }
    /**
     * Display a listing of the resource.
     */
    // Mostra um funcionário
    public function show($id)
    {
        $funcionario = Funcionario::find($id);
        if (!$funcionario) {
            return redirect()->back()->with("ERRO", "FUNCIONÁRIO NÃO ENCONTRADO!");
        }

        return view('pages.admin.funcionario', compact('funcionario'));
    }
    /**
     * Display a listing of the resource.
     */
    // Remove um funcionário
    public function destroy($id)
    {
        $funcionario = Funcionario::find($id);
        if (!$funcionario) {
            return redirect()->back()->with("ERRO", "FUNCIONÁRIO NÃO ENCONTRADO.");
        }

        $funcionario->delete();
        return redirect()->back()->with("SUCESSO", "FUNCIONÁRIO APAGADO COM SUCESSO");
    }
}

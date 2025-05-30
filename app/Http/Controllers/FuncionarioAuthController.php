<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuncionarioAuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login do funcionário
     */
    public function login(Request $request)
    {
        // Validação do formulário
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Verifica credenciais
        if (Auth::attempt($credentials)) {
            $user = Auth::funcionario();

            // Redireciona conforme o tipo de usuário
            if ($user->funcao === 'farmaceutico') {
                return redirect()->route('vendas.index');
            } elseif ($user->tipo === 'gerente') {
                return redirect()->route('dashboard'); // Certifica-te que essa rota existe
            } else {
                // Tipo desconhecido
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tipo de usuário não autorizado.',
                ]);
            }
        }

        // Credenciais inválidas
        return back()->withErrors([
            'email' => 'Email ou senha inválidos.',
        ])->withInput();
    }

    /**
     * Faz logout do sistema
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

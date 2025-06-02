<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Funcionario;

class FuncionarioAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required']
        ]);

        $email = $request->input('email');
        $senha = $request->input('senha');

        $user = Funcionario::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email não encontrado.'])->withInput();
        }

        if (!Hash::check($senha, $user->senha)) {
            return back()->withErrors(['senha' => 'Senha incorreta.'])->withInput();
        }

        // Faz login manualmente
        Auth::guard('funcionario')->login($user);

        $request->session()->regenerate();

        if ($user->funcao === 'Farmaceutico') {
            return redirect()->route('vendas.index');
        } elseif ($user->funcao === 'Gerente') {
            return redirect()->route('dashboard');
        }

        Auth::guard('funcionario')->logout();
        return back()->withErrors(['email' => 'Usuário não autorizado.'])->withInput();
    }

    public function logout(Request $request)
    {
        // Salvar a função antes do logout
        $funcao = Auth::guard('funcionario')->user()?->funcao;

        Auth::guard('funcionario')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Agora você ainda consegue usar $funcao
        if ($funcao === 'Gerente') {
            return redirect()->route('dashboard'); // redireciona para dashboard
        }

        return redirect()->route('login'); // redireciona para login
    }
}

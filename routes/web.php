<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FuncionarioController,
    ProdutoController,
    StockController,
    VendaController,
    FuncionarioAuthController,
};

// ===============================
// ROTAS PÚBLICAS (sem autenticação)
// ===============================
Route::get('/', [FuncionarioAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [FuncionarioAuthController::class, 'login'])->name('funcionario.login');
Route::post('/logout', [FuncionarioAuthController::class, 'logout'])->name('logout');

// =======================================
// ROTAS PRIVADAS (somente usuários logados)
// =======================================
Route::group(['middleware'=>'auth'],function(){
    // Dashboard do gerente
    Route::get('/dashboard', function () {
        return view('pages.admin.index');
    })->name('dashboard');

    // ================= FUNCIONÁRIO =================

    // CRUD automático (index, create, store, show, edit, update, destroy)
    Route::resource('/funcionario', FuncionarioController::class);

    // Rota personalizada para armazenar um novo funcionário
    Route::post('/funcionario/store', [FuncionarioController::class,'store'])->name('funcionario.store');

    // Rota para excluir um funcionário pelo ID
    Route::get('/funcionario/destroy/{id}', [FuncionarioController::class,'destroy'])->name('funcionario.destroy');

    // ================= PRODUTO =================

    // CRUD automático de produtos
    Route::resource('/produto', ProdutoController::class);

    // Armazena um novo produto
    Route::post('/produto/store', [ProdutoController::class,'store'])->name('produto.store');

    // Remove um produto pelo ID
    Route::get('/produto/destroy/{id}', [ProdutoController::class,'destroy'])->name('produto.destroy');

    // ================= STOCK =================

    // Armazena novo stock
    Route::post('/stock/store', [StockController::class, 'store'])->name('stock.store');

    // Remove um item de stock
    Route::get('/stock/destroy/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

    // Lista o stock ou um stock específico (opcional: id)
    Route::get('/stocks/{id?}', [StockController::class, 'index'])->name('stock.index');

    // ================= VENDAS =================

    // Página principal de vendas
    Route::get('/vendas', [VendaController::class, 'index'])->name('vendas.index');

    // Adiciona item ao carrinho
    Route::post('/vendas/adicionar', [VendaController::class, 'addToCart'])->name('vendas.add');

    // Remove item do carrinho
    Route::get('/vendas/remover/{id}', [VendaController::class, 'removeFromCart'])->name('vendas.remove');

    // Limpa o carrinho
    Route::get('/vendas/limpar', [VendaController::class, 'clearCart'])->name('vendas.clear');

    // Finaliza a venda
    Route::post('/vendas/finalizar', [VendaController::class, 'checkout'])->name('vendas.checkout');

    Route::get('/vendas/relatorio', [VendaController::class, 'relatorio'])->name('vendas.relatorio');

    Route::get('/vendas/relatorio/pdf', [VendaController::class, 'exportarPDF'])->name('vendas.relatorio.pdf');

    Route::get('/devolucoes', [VendaController::class, 'devolucao'])->name('devolucoes.devolucao');
    Route::delete('/devolucoes/{id}', [VendaController::class, 'eliminarVenda'])->name('devolucoes.eliminar');

});
//Auth::routes();

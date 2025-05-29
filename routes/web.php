<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\{
    FuncionarioController,
    ProdutoController,
    StockController,
    VendaController
};
Route::get('/', function () {
    return view('pages.admin.index');
});
Route::resource('/funcionario', FuncionarioController::class,);
Route::post('/funcionario/store', [FuncionarioController::class,'store'])->name('funcionario.store');
Route::get('/funcionario/destroy/{id}',[FuncionarioController::class,'destroy'])->name('funcionario.destroy');

Route::resource('/produto',ProdutoController::class);
Route::post('/produto/store',[ProdutoController::class,'store'])->name('produto.store');
Route::get('/produto/destroy/{id}',[ProdutoController::class,'destroy'])->name('produto.destroy');

Route::post('/stock/store',[StockController::class,'store'])->name('stock.store');
Route::get('/stock/destroy/{id}',[StockController::class,'destroy'])->name('stock.destroy');
Route::get('/stocks/{id?}', [StockController::class, 'index'])->name('stock.index');

Route::get('/vendas', [VendaController::class, 'index'])->name('vendas.index');
Route::post('/vendas/adicionar', [VendaController::class, 'addToCart'])->name('vendas.add');
Route::get('/vendas/remover/{id}', [VendaController::class, 'removeFromCart'])->name('vendas.remove');
Route::get('/vendas/limpar', [VendaController::class, 'clearCart'])->name('vendas.clear');
Route::post('/vendas/finalizar', [VendaController::class, 'checkout'])->name('vendas.checkout');
Route::get('/vendas/comprovativo/{venda}', [VendaController::class, 'comprovativo'])->name('vendas.comprovativo');

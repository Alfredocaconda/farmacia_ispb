<?php

namespace App\Http\Controllers;

use App\Models\venda;
use App\Models\stock;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stock = stock::with('produto')->get();
        return view('pages.admin.vendas', compact('stock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(venda $venda)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(venda $venda)
    {
        //
    }
}

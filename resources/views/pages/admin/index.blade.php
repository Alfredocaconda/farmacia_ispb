@extends('layouts.app')
@section('title', 'DASHBOARD')
@section('principal')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-info-light">
                                    <img src="{{asset('images/page-img/16.jpg')}}" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <a href="{{ route('funcionario.index') }}">
                                        <p class="mb-2">Total de Funcionário</p>
                                    <h4>{{ \App\Models\Funcionario::count() }}</h4></a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-danger-light">
                                    <img src="{{asset('images/stock.png')}}" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <a href="{{ route('stock.index') }}">
                                        <p class="mb-2">Total Stock</p>
                                    <h4>{{ \App\Models\Stock::sum('qtd_stock') }}</h4>
                                </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                            
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-success-light">
                                    <img src="{{asset('images/dinheiro.png')}}" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <a href="{{ route('vendas.relatorio') }}">
                                        <p class="mb-2">Total de Vendas</p>
                                    <h4>{{ \App\Models\venda::sum('quantidade') }}</h4>
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4 card-total-sale">
                                <div class="icon iq-icon-box-2 bg-success-light">
                                    <img src="{{asset('images/caducidade.png')}}" class="img-fluid" alt="image">
                                </div>
                                <div>
                                    <a href="{{ route('vendas.relatorio') }}">
                                        <p class="mb-2">Produtos Caducados</p>
                                    <h4>{{ \App\Models\venda::sum('quantidade') }}</h4>
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>
@endsection

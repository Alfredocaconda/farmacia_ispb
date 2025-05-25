@extends('layouts.app')
@section('title', 'STOCK')
@section('stock')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title" style="display: flex; justify-content: space-between; width: 100%">
                    <h4 class="card-title">Cadastrar STOCK</h4>
                    <a href="#Cadastrar" data-toggle="modal" style="font-size: 20pt"><i class="fa fa-plus-circle"></i></a>
                </div>
            </div>
            @if(session('Error'))
                    <div class="alert alert-danger">
                        <p>{{session('Error')}}</p>
                    </div>
                @endif
                @if(session('Sucesso'))
                    <div class="alert alert-success">
                        <p>{{session('Sucesso')}}</p>
                    </div>
                @endif
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table data-tables table-striped">
                    <thead>
                        <tr class="ligth">
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Funcionario</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($stock as $dados)
                            <tr>
                                <td>{{$dados->produto->nome}}</td>
                                <td>{{$dados->preco}}</td>
                                <td>{{$dados->quantidade}}</td>
                                <td>{{$dados->caducidade}}</td>
                                <td>{{$dados->funcionario->nome}}</td>
                                <td>
                                    <a href="#Cadastrar" data-toggle="modal" class="text-primary" onclick="editar({{$dados}})" ><i class="fa fa-edit"></i></a>
                                    <a href="{{route('stock.destroy',$dados->id)}}" class="text-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Cadastrar" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">DAR ENTRADA NO STOCK</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
            <div class="modal-body">
                <div class="container-fluid">
                   <form action="{{route('stock.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                         <input type="hidden" name="id" id="id">
                       @if($valor)
                            <input type="hidden" name="id_produto" id="id_produto" value="{{ $valor->id }}">
                            <p><strong>Nome do Produto : </strong>{{ $valor->nome }}</p>
                        @else
                            <p><strong>Selecione um produto para dar entrada no stock.</strong></p>
                        @endif

                         <div class="form-group">
                            <label for="preco">Preço</label>
                            <div class="form-input">
                                <input type="number" name="preco" id="preco" class="form-control" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="qtd_stock">Quantidade</label>
                            <div class="form-input">
                                <input type="number" name="qtd_stock" id="qtd_stock" class="form-control" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="caducidade">Caducidade</label>
                            <div class="form-input">
                                <input type="date" name="caducidade" id="caducidade" class="form-control" />
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <x-botao-form />
            </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editar(valor) {
        document.getElementById('id').value = valor.id;
        document.getElementById('preco').value = valor.preco;
        document.getElementById('qtd_stock').value = valor.qtd_stock;
        document.getElementById('caducidade').value = valor.caducidade;
    }
    function limpar() {
        document.getElementById('id').value = "";
        document.getElementById('qtd_stock').value = "";
        document.getElementById('caducidade').value = "";
        document.getElementById('preco').value = "";
    }
</script>
@endsection

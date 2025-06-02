@extends('layouts.app')
@section('title', 'Produto')
@section('produto')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title" style="display: flex; justify-content: space-between; width: 100%">
                    <h4 class="card-title">Cadastrar Produtos</h4>
                    <a href="#Cadastrar" data-toggle="modal" style="font-size: 20pt"><i class="fa fa-plus-circle"></i></a>
                </div>
            </div>
            {{-- Mensagem de Sucesso --}}
            @if (session('SUCESSO'))
                <div class="alert alert-green">
                    {{ session('SUCESSO') }}
                </div>
            @endif

            {{-- Mensagem de Erro Geral --}}
            @if (session('ERRO'))
                <div class="alert alert-danger">
                    {{ session('ERRO') }}
                </div>
            @endif

            {{-- Erros de Validação --}}
            @if ($errors->any())
                <div class="alert alert-iq-warning">
                    <ul>
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table data-tables table-striped">
                    <thead>
                        <tr class="ligth">
                            <th>NOME DO PRODUTO</th>
                            <th>DESCRIÇÃO</th>
                            <th>CATEGÓRIA</th>
                            <th>FUNCIONÁRIO</th>
                            <th>OPÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($valor as $dados)
                            <tr>
                                <td>{{$dados->nome}}</td>
                                <td>{{$dados->descricao}}</td>
                                <td>{{$dados->categoria}}</td>
                                <td>{{$dados->funcionario->nome}}</td>
                                <td>
                                    <a href="#Cadastrar" data-toggle="modal" class="text-primary" onclick="editar({{$dados}})" ><i class="fa fa-edit"></i></a>
                                    <a href="{{route('produto.destroy',$dados->id)}}" class="text-danger"><i class="fa fa-trash"></i></a>
                                </td>
                                <td>
                                    <a href="{{route('stock.index',$dados->id)}}" class="text-danger">DAR ENTRADA</a>
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
                        <h5 class="modal-title">Cadastrar Produto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
            <div class="modal-body">
                <div class="container-fluid">
                  <form action="{{ route('produto.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <x-input-normal id="nome" name="nome" type="text" titulo="NOME DO PRODUTO" alert="" />
                            <x-input-normal id="descricao" name="descricao" type="text" titulo="DESCRIÇÃO" alert="" />
                            <x-input-normal id="categoria" name="categoria" type="text" titulo="CATEGÓRIA" alert="" />
                        </div>
                        <div class="modal-footer">
                            <x-botao-form />
                        </div>
                    </form>

            </div>
        </div>
    </div>
</div>

<script>
    function editar(valor) {
        document.getElementById('id').value = valor.id;
        document.getElementById('nome').value = valor.nome;
        document.getElementById('descricao').value = valor.descricao;
        document.getElementById('categoria').value = valor.categoria;
    }
    function limpar() {
        document.getElementById('id').value = "";
        document.getElementById('nome').value = "";
        document.getElementById('descricao').value = "";
        document.getElementById('categoria').value = "";
    }
</script>
@endsection

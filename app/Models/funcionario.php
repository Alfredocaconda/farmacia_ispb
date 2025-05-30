<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Funcionario extends Authenticatable
{
    use Notifiable;

    // seus atributos e métodos aqui
    protected $fillable = [
        'name',
        'email',
        'telefone',
        'endereco',
        'data_entrada',
        'n_bilhete',
        'funcao',
        'senha'
    ];
      // Sobrescreve o método getAuthPassword para usar o campo 'senha'
    public function getAuthPassword()
    {
        return $this->senha;
    }
}

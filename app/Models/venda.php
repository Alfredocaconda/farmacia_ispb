<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class venda extends Model
{
    //
    public function produto(){
        return $this->belongsTo(Produto::class);
    }
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}

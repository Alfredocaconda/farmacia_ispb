<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class produto extends Model
{
    //
    public function funcionario(){
        return $this->belonsTo(funcionario::class,'id_funcionario');
    }
    public function stock(){
        return $this->hasOne(stock::class,'id_stock');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormularioGeralDeleted extends Model
{
    protected $table = 'formulario_geral_deleted';

    protected $fillable = [
        'registro_id',
    ];
}

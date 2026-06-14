<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaMaterial extends Model
{
    protected $table = 'categorias_material';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
    ];
}

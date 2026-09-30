<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'producto',
        'cantidad_vendida', // <--- DEBE ESTAR AQUÍ
        'precio_venta',
        'precio_importacion',
        'ganancia'
    ];
}
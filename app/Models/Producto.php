<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'marca',
        'tipo_medida',
        'cantidad',
        'precio_compra',
        'precio_venta',
        'precio_unidad',
        'precio_media_docena',
        'precio_una_docena',
        'precio_compra_caja',   // ✅ AGREGADO
        'precio_venta_caja',    // ✅ AGREGADO
    ];

    // Relación con la tabla de cables
    public function cable()
    {
        return $this->hasOne(Cable::class, 'producto_id');
    }
}
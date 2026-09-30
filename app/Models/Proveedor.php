<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    // Agrega esta línea para permitir que Eloquent guarde estos campos masivamente
    protected $fillable = [
        'nombre_completo',
        'nombre_empresa',
        'contacto',
    ];
}
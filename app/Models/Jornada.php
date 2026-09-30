<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornada extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'mes',
        'anio',
        'total_ventas',
        'total_ganancia',
        'total_productos_vendidos',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total_ventas' => 'decimal:2',
        'total_ganancia' => 'decimal:2',
    ];

    /**
     * Relación: Una jornada tiene muchas ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    /**
     * ✅ NUEVO: Verificar si la jornada está cerrada
     */
    public function estaCerrada()
    {
        return $this->estado === 'cerrada';
    }

    /**
     * ✅ NUEVO: Verificar si la jornada está abierta
     */
    public function estaAbierta()
    {
        return $this->estado === 'abierta';
    }

    /**
     * ✅ NUEVO: Recalcular totales de la jornada
     * Se ejecuta automáticamente al registrar/eliminar ventas
     */
    public function recalcularTotales()
    {
        $this->total_ventas = $this->ventas()->sum('subtotal_venta') ?? 0;
        $this->total_ganancia = $this->ventas()->sum('ganancia') ?? 0;
        $this->total_productos_vendidos = $this->ventas()->sum('cantidad') ?? 0;
        $this->save();
    }

    /**
     * ✅ NUEVO: Obtener nombre del mes en español
     * Uso en vista: {{ $jornada->nombre_mes }}
     */
    public function getNombreMesAttribute()
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        
        return $meses[$this->mes] ?? 'Mes desconocido';
    }

    /**
     * ✅ NUEVO: Scope para filtrar por mes y año
     */
    public function scopeDelMes($query, $mes, $anio)
    {
        return $query->where('mes', $mes)->where('anio', $anio);
    }

    /**
     * ✅ NUEVO: Scope para filtrar por año
     */
    public function scopeDelAnio($query, $anio)
    {
        return $query->where('anio', $anio);
    }
}
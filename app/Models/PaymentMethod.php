<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'fee_percentage',
        'bank_account',
        'empresa_id',
        'is_active',
    ];

    protected $casts = [
        'fee_percentage' => 'float',
        'is_active' => 'boolean',
    ];

    // Mapear os tipos para descrições
    public static $typeDescriptions = [
        'D' => 'Dinheiro',
        'C' => 'Crédito',
        'T' => 'Débito',
        'P' => 'Pix',
    ];

    /**
     * Obter descrição do tipo.
     */
    public function getTypeDescriptionAttribute()
    {
        return self::$typeDescriptions[$this->type] ?? 'Desconhecido';
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}

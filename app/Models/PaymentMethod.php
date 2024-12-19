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
        'is_active',
    ];

    /**
     * Casts para os tipos de dados.
     */
    protected $casts = [
        'fee_percentage' => 'float',
        'is_active' => 'boolean',
    ];
}

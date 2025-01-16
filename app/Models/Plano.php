<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'id_produto_stripe',
        'valor_mensal',
        'valor_total',
        'duracao_dias',
        'quantidade',
    ];
}

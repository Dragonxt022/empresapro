<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaOperacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'caixa_movimento_id',
        'tipo',
        'valor',
        'descricao',
        'data_hora',
    ];

    // Relacionamento com Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relacionamento com CaixaMovimento
    public function caixaMovimento()
    {
        return $this->belongsTo(CaixaMovimento::class);
    }
}

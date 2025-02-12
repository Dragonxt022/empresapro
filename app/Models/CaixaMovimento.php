<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaMovimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'data_hora_abertura',
        'data_hora_fechamento',
        'valor_inicial',
        'valor_final',
        'diferenca',
        'status',
    ];

    // Relacionamento com Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relacionamento com CaixaOperacoes
    public function operacoes()
    {
        return $this->hasMany(CaixaOperacao::class, 'caixa_movimento_id');
    }


    // Relacionamento com Vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'caixa_movimento_id');
    }
}

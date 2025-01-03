<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaOperacao extends Model
{
    use HasFactory;
    protected $table = 'caixa_operacoes';
    protected $fillable = [
        'empresa_id',
        'caixa_movimento_id',
        'tipo',
        'valor',
        'descricao',
        'data_hora',
    ];


    // Relacionamento com CaixaMovimento
    public function caixaMovimento()
    {
        return $this->belongsTo(CaixaMovimento::class, 'caixa_movimento_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


}

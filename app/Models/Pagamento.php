<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'venda_id',
        'metodo',
        'valor',
    ];

    // Relacionamento com Venda
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }


}

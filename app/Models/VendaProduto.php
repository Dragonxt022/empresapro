<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaProduto extends Model
{
    use HasFactory;

    protected $fillable = [
        'venda_id',
        'product_id',
        'nome',
        'valor_unitario',
        'quantidade',
        'valor_total',
    ];

    // Relacionamento com Produto
    public function produto()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    // Relacionamento com Venda
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}

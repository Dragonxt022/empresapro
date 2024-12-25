<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = [
        'mesa_id',
        'empresa_id',
        'cliente',
        'vendido_por',
        'desconto',
        'acrescimo',
        'valor_total',
        'status',
    ];

    // Relacionamento com Mesa
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    // Relacionamento com Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relacionamento com Pagamentos
    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class);
    }

    // Relacionamento com Produtos da Venda
    public function produtos()
    {
        return $this->hasMany(VendaProduto::class);
    }

    // Relacionamento com Usuário (quem vendeu)
    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendido_por');
    }
}

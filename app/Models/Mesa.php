<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'status',
    ];

    // Relacionamento com Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relacionamento com Vendas
    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'valor_mensal',
        'valor_total',
        'dias',
        'descricao',
        'status',
    ];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'valor_mensal' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'status' => 'boolean',
    ];

    // Relacionamento: Uma assinatura pode ter muitas empresas
    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'website',
        'social_media',
        'logo',
        'fiscal_status',
        'company_type',
        'operating_since',
        'status',
        'assinatura_id',
    ];

    protected $casts = [
        'social_media' => 'array', // Aqui garantimos que seja tratado como um array JSON
    ];

    /**
     * Relacionamento: Empresa pode ter muitos usuários
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relacionamento: Empresa pode ter muitas categorias
     */
    public function categories()
    {
        return $this->hasMany(Categorie::class);
    }

    /**
     * Relacionamento: Empresa pode ter muitos fornecedores
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * Relacionamento: Empresa pode ter muitos produtos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function operacoes()
    {
        return $this->hasMany(CaixaOperacao::class, 'empresa_id');
    }

    // Relacionamento de pertence a uma assinatura
    public function assinatura()
    {
        return $this->belongsTo(Assinatura::class);
    }
}

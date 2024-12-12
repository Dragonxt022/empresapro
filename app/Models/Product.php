<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'description',
        'category_id',
        'price',
        'cost_price',
        'stock_quantity',
        'min_stock',
        'empresa_id',
        'is_active',
        'is_managed',
        'image_path',
        'ncm_code',
        'icms',
        'supplier_id',
        'expiration_date',
    ];

    // Método booted() para aplicar o escopo global
    public function getImagePathAttribute($value)
    {
        return $value ? $value : 'default-product-image.jpg';
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    // Relacionamento com o fornecedor
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relacionamento com variações
    public function variations()
    {
        return $this->hasMany(Product_variation::class);
    }

}

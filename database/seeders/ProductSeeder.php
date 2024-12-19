<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\Supplier; // Caso precise de fornecedores

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Gerando 1000 produtos
        foreach (range(1, 1000) as $index) {
            Product::create([
                'name' => $faker->word, // Nome aleatório
                'sku' => $faker->uuid, // SKU único
                'barcode' => $faker->ean13, // Código de barras
                'description' => $faker->sentence, // Descrição
                'category_id' => Categorie::inRandomOrder()->first()->id, // Categoria aleatória
                'price' => $faker->randomFloat(2, 5, 500), // Preço entre 5 e 500
                'cost_price' => $faker->randomFloat(2, 1, 200), // Preço de custo entre 1 e 200
                'stock_quantity' => $faker->numberBetween(0, 100), // Quantidade em estoque
                'min_stock' => $faker->numberBetween(0, 50), // Estoque mínimo
                'empresa_id' => 1, // Exemplo de empresa_id fixo
                'is_active' => $faker->boolean, // Ativo ou inativo
                'is_managed' => $faker->boolean, // Gerenciado ou não
                'image_path' => null, // Caminho da imagem (pode ser personalizado)
                'ncm_code' => $faker->regexify('[0-9]{8}'), // Código NCM aleatório
                'icms' => $faker->randomFloat(2, 0, 18), // ICMS aleatório entre 0% e 18%
                'supplier_id' => null,
                'expiration_date' => $faker->dateTimeBetween('now', '+2 years'), // Data de validade
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

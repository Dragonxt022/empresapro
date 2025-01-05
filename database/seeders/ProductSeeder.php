<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $empresa_id = config('app.empresa_id');

        // Criando categorias relacionadas a lanches e comida rápida
        $categories = [
            'Hamburgueres' => 'hamburgueres.jpg',
            'Batatas Fritas' => 'batatas_fritas.jpg',
            'Bebidas' => 'bebidas.jpg',
            'Sanduíches' => 'sanduiches.jpg',
            'Doces e Sobremesas' => 'doces_sobremesas.jpg',
        ];

        // Criação das categorias no banco de dados
        foreach ($categories as $categoryName => $categoryImage) {
            Categorie::create([
                'name' => $categoryName,
                'empresa_id' => $empresa_id,
                'image_path' => 'images/' . $categoryImage, // Definindo o caminho da imagem
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Definindo nomes fixos para os produtos do ramo de lanches
        $productNames = [
            'Hamburguer Clássico' => 'hamburguer_classico.jpg',
            'Batata Frita Grande' => 'batata_frita_grande.jpg',
            'Refrigerante Lata' => 'refrigerante_lata.jpg',
            'Sanduíche de Frango' => 'sanduiche_frango.jpg',
            'Milkshake de Chocolate' => 'milkshake_chocolate.jpg'
        ];

        // Gerando 5 produtos de lanches
        foreach (range(0, 4) as $index) {
            Product::create([
                'name' => array_keys($productNames)[$index], // Nome fixo
                'sku' => $faker->uuid, // SKU único
                'barcode' => $faker->ean13, // Código de barras
                'description' => $faker->sentence, // Descrição
                'category_id' => Categorie::inRandomOrder()->first()->id, // Categoria aleatória
                'price' => $faker->randomFloat(2, 5, 50), // Preço entre 5 e 50 (para lanches e bebidas)
                'cost_price' => $faker->randomFloat(2, 1, 20), // Preço de custo entre 1 e 20
                'stock_quantity' => $faker->numberBetween(0, 100), // Quantidade em estoque
                'min_stock' => $faker->numberBetween(0, 50), // Estoque mínimo
                'empresa_id' => $empresa_id, // Usando o ID da empresa passada como parâmetro
                'is_active' => $faker->boolean, // Ativo ou inativo
                'is_managed' => $faker->boolean, // Gerenciado ou não
                'image_path' => 'images/' . $productNames[array_keys($productNames)[$index]], // Caminho da imagem
                'ncm_code' => $faker->regexify('[0-9]{8}'), // Código NCM aleatório
                'icms' => $faker->randomFloat(2, 0, 18), // ICMS aleatório entre 0% e 18%
                'supplier_id' => null, // Sem fornecedor por enquanto
                'expiration_date' => $faker->dateTimeBetween('now', '+2 years'), // Data de validade
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

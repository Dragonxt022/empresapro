<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa_id = config('app.empresa_id');

        // Definindo as categorias com o ID da empresa recebido como parâmetro
        $categories = [
            ['name' => 'Lanches', 'description' => 'Lanches variados', 'empresa_id' => $empresa_id],
            ['name' => 'Bebidas', 'description' => 'Bebidas variadas', 'empresa_id' => $empresa_id],
            ['name' => 'Sucos', 'description' => 'Sucos naturais', 'empresa_id' => $empresa_id],
            ['name' => 'Salgados', 'description' => 'Salgados fritos', 'empresa_id' => $empresa_id],
            ['name' => 'Doces', 'description' => 'Doces caseiros', 'empresa_id' => $empresa_id],
        ];

        // Criando as categorias no banco de dados
        foreach ($categories as $category) {
            Categorie::create($category); // Usando a variável corrigida para o nome da classe "Category"
        }
    }
}

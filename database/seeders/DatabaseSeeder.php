<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Assinaturas
        $this->call(AssinaturaSeeder::class);

        // Empresa e Admim
        $this->call(AdminUserSeeder::class);

        // // Categorias
        // $this->call(CategorySeeder::class);

        // //Produtos
        // $this->call(ProductSeeder::class);

        // // Metodos Pagamentos
        // $this->call(PaymentMethodSeeder::class);

        // // Mesas
        // $this->call(MesaSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AssinaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Assinatura gratuita
        DB::table('assinaturas')->insert([
            'nome' => 'Plano Grátis',
            'valor_mensal' => 0, // 0 centavos
            'valor_total' => 0,  // 0 centavos
            'dias' => 3,
            'descricao' => 'Assinatura gratuita por 3 dias.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assinatura básica
        DB::table('assinaturas')->insert([
            'nome' => 'Plano Básico',
            'valor_mensal' => 10000, // R$ 100,00 em centavos
            'valor_total' => 10000,  // R$ 100,00 em centavos
            'dias' => 30,
            'descricao' => 'Assinatura básica por 30 dias.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assinatura premium
        DB::table('assinaturas')->insert([
            'nome' => 'Plano Premium',
            'valor_mensal' => 9590,   // R$ 95,90 em centavos
            'valor_total' => 28770,  // R$ 287,70 em centavos
            'dias' => 90,
            'descricao' => 'Assinatura premium por 90 dias.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assinatura Enterprise
        DB::table('assinaturas')->insert([
            'nome' => 'Plano Enterprise',
            'valor_mensal' => 8590,   // R$ 85,90 em centavos
            'valor_total' => 103080, // R$ 1.030,80 em centavos
            'dias' => 365,
            'descricao' => 'Assinatura Enterprise por 365 dias.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

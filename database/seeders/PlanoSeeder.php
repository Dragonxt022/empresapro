<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plano::create([
            'nome' => 'Free',
            'id_produto_stripe' => null,
            'valor_mensal' => 0.00,
            'valor_total' => 0.00,
            'duracao_dias' => 3,
            'quantidade' => 1,
        ]);

        Plano::create([
            'nome' => 'Básico',
            'id_produto_stripe' => 'prod_RXVOLolEMRjXZ9',
            'valor_mensal' => 100.00,
            'valor_total' => 100.00,
            'duracao_dias' => 30,
            'quantidade' => 1,
        ]);

        Plano::create([
            'nome' => 'Premium',
            'id_produto_stripe' => 'prod_RXVTRZkP3N1Jkx',
            'valor_mensal' => 95.90,
            'valor_total' => 287.70,
            'duracao_dias' => 90,
            'quantidade' => 3,
        ]);

        Plano::create([
            'nome' => 'Enterprise',
            'id_produto_stripe' => 'prod_RXVbCWOiHnsbOt',
            'valor_mensal' => 85.90,
            'valor_total' => 1030.80,
            'duracao_dias' => 365,
            'quantidade' => 12,
        ]);
    }
}

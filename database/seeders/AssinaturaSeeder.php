<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assinatura;
use App\Models\Plano; // Certifique-se de importar o modelo Plano
use Carbon\Carbon;

class AssinaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recupera o ID da empresa atual a partir da configuração
        $empresa_id = config('app.empresa_id');

        // Verifica se o ID da empresa está definido
        if ($empresa_id) {
            // Recupera o plano 'God' (ou o plano desejado) do banco de dados
            $planoGod = Plano::where('nome', 'God')->first();

            // Cria a assinatura associada à empresa e ao plano
            Assinatura::create([
                'empresa_id' => $empresa_id,
                'plano_id' => $planoGod->id, // Associa o ID do plano
                'valor' => $planoGod->valor_total,
                'inicio' => Carbon::now(),
                'fim' => Carbon::now()->addDays($planoGod->duracao_dias),
                'status' => 'ativa',
            ]);
        }
    }
}

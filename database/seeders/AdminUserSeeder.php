<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Assinatura; // Importar o modelo Assinatura
use App\Models\Plano;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criação da empresa 'Provendas'
        $empresa = Empresa::create([
            'name' => 'Provendas',
            'cnpj' => '12.345.678/0001-90',  // Exemplo de CNPJ
            'phone' => '11 98765-4321', // Exemplo de telefone
            'address' => 'Rua Exemplo, 123',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01234-567',
            'website' => 'https://provendas.com.br',
            'social_media' => json_encode(['facebook' => 'https://facebook.com/provendas']),
            'logo' => 'provendas-logo.png', // Logo da empresa (caminho fictício)
            'fiscal_status' => 'Ativa',  // Status fiscal da empresa
            'company_type' => 'LTDA', // Tipo de empresa
            'operating_since' => '2010-01-01', // Data de início da operação
            'status' => true, // Empresa ativa
        ]);

        // Criação do usuário administrador
        $user = User::create([
            'first_name' => 'Administrador',
            'last_name' => 'Provendas',
            'email' => 'admin@provendas.com.br', // E-mail do admin
            'password' => Hash::make('Pissinet@2021'), // Senha segura para o admin
            'empresa_id' => $empresa->id, // Associa o usuário à empresa Provendas criada
            'role' => 'admin', // Define o papel como 'admin'
            'is_active' => true, // Usuário ativo
        ]);

        // Recupera o plano 'Free' para atribuir à empresa
        $planoFree = Plano::where('nome', 'Free')->first();  // Aqui o nome está correto
        Log::info('Plano Free: ', $planoFree ? $planoFree->toArray() : ['erro' => 'Plano não encontrado']);

        if ($planoFree) {
            // Cria a assinatura para a empresa com duração indefinida (null em 'fim')
            $assinatura = Assinatura::create([
                'plano' => $planoFree->nome,
                'valor' => 0,
                'inicio' => Carbon::now(),
                'fim' => Carbon::now()->addDays(100000), // Duração indefinida
                'status' => 'ativa',
                'empresa_id' => $empresa->id, // Associa a assinatura à empresa
                'plano_id' => $planoFree->id, // Associa o plano 'Free' à assinatura
            ]);

            // Associa a assinatura à empresa
            $empresa->assinatura_id = $assinatura->id;
            $empresa->save();
        }
    }
}

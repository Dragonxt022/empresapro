<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'assinatura_id' => 1, // ID da assinatura (ajustar conforme necessário)
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
    }
}

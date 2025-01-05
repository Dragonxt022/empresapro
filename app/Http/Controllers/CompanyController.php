<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class CompanyController extends Controller
{
    public function store(Request $request)
    {


        // Valida os dados da empresa e do usuário
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'website' => 'nullable|string',
            'social_media' => 'nullable|string',
            'logo' => 'nullable|string',
            'fiscal_status' => 'nullable|string',
            'company_type' => 'required|string',
            'operating_since' => 'nullable|string',
            'status' => 'nullable|string',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Validação de confirmação de senha
        ]);

        // Incluindo o id da assinatura como 1
        $validated['assinatura_id'] = 1; // Adiciona o id da assinatura automaticamente

        // Cria a empresa
        $company = Empresa::create($validated);

        // Cria o usuário e associa à empresa
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Garante que a senha será armazenada de forma segura
            'empresa_id' => $company->id, // Associa o usuário à empresa recém-criada
        ]);


        // Rodando os seeders relacionados à empresa recém-criada
        $this->runSeeders($company->id);

        // Redireciona para a página de login após o cadastro
        return redirect()->route('login'); // A rota 'login' do Laravel


    }

    /**
     * Função que executa os seeders específicos para a empresa.
     */
    protected function runSeeders($companyId)
    {
        // Configurando a variável global com o ID da empresa
        config(['app.empresa_id' => $companyId]);

        // Rodando os seeders

        Artisan::call('db:seed', ['--class' => 'ProductSeeder']);
        Artisan::call('db:seed', ['--class' => 'PaymentMethodSeeder']);
        Artisan::call('db:seed', ['--class' => 'MesaSeeder']);
    }
}

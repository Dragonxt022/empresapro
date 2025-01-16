<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Assinatura; // Importar o modelo Assinatura
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

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

        // Recupera o plano 'Free' para atribuir à empresa
        $planoFree = Plano::where('nome', 'Free')->first();
        if (!$planoFree) {
            return response()->json(['error' => 'Plano não encontrado'], 404);
        }

        // Cria a empresa
        $company = Empresa::create($validated);

        // Cria a assinatura 'Free' para a empresa com duração de 3 dias
        $assinatura = Assinatura::create([
            'plano' => $planoFree->nome,
            'valor' => 0,
            'inicio' => Carbon::now(),
            'fim' => Carbon::now()->addDays(3), // Duração de 3 dias
            'status' => 'ativa',
            'empresa_id' => $company->id,
            'plano_id' => $planoFree->id, // Associa o plano 'Free' à assinatura
        ]);

        // Associa a assinatura à empresa
        $company->assinatura_id = $assinatura->id;
        $company->save(); // Atualiza a empresa com o ID da assinatura

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
        return redirect()->route('login');
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

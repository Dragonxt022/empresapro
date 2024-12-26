<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    /**
     * Listar formas de pagamento da empresa do usuário autenticado.
     */
    public function index()
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Busca as formas de pagamento da empresa do usuário autenticado
        $paymentMethods = PaymentMethod::where('empresa_id', $empresaId)->get();

        // Renderiza a página com os dados usando o Inertia
        return Inertia::render('FormaDePagamentos/Index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }



    public function apiPaymentMethods(Request $request)
    {
        Log::info('Rota apiPaymentMethods foi chamada');

        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Busca as formas de pagamento da empresa do usuário autenticado
        $paymentMethods = PaymentMethod::where('empresa_id', $empresaId)->get();

        return response()->json($paymentMethods);
    }


    /**
     * Criar uma nova forma de pagamento.
     */
    public function store(Request $request)
    {
        // Recupera o usuário logado e o ID da empresa
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Validação dos dados recebidos da requisição
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:D,C,T,P',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'bank_account' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'O nome da forma de pagamento é obrigatório.',
            'type.required' => 'O tipo de pagamento é obrigatório.',
            'type.in' => 'O tipo de pagamento deve ser Dinheiro, Crédito, Débito ou Pix.',
            'fee_percentage.required' => 'A taxa é obrigatória.',
            'fee_percentage.numeric' => 'A taxa deve ser um número.',
            'fee_percentage.min' => 'A taxa deve ser maior ou igual a 0.',
            'fee_percentage.max' => 'A taxa deve ser menor ou igual a 100.',
        ]);

        // Adiciona o ID da empresa ao array de dados validados
        $validatedData['empresa_id'] = $empresaId;

        try {
            // Cria o novo método de pagamento
            $paymentMethod = PaymentMethod::create($validatedData);

            // Redireciona de volta com mensagem de sucesso
            return back()->with('flash', [
                'message' => 'Método "' . $validatedData['name'] . '" cadastrado com sucesso!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, redireciona de volta com mensagem de erro
            return back()->with('flash', [
                'message' => 'Erro ao cadastrar o método "' . $validatedData['name'] . '". Erro: ' . $e->getMessage(),
                'type' => 'error', // Corrigido para 'error'
            ]);
        }
    }


    /**
     * Atualizar uma forma de pagamento.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // dd($paymentMethod->toArray());
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Verificar se a forma de pagamento pertence à empresa do usuário
        if ($paymentMethod->empresa_id !== $empresaId) {
            Log::debug('Empresa do usuário: ' . $empresaId);
            Log::debug('Empresa da forma de pagamento: ' . $paymentMethod->empresa_id);
            Log::debug('Usuário autenticado: ' . Auth::user()->id);

            return response()->json(['error' => 'Não autorizado'], 403);
        }

        // Validação dos dados enviados no request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:D,C,T,P',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'bank_account' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'O nome da forma de pagamento é obrigatório.',
            'type.required' => 'O tipo de pagamento é obrigatório.',
            'type.in' => 'O tipo de pagamento deve ser Dinheiro, Crédito, Débito ou Pix.',
            'fee_percentage.required' => 'A taxa é obrigatória.',
            'fee_percentage.numeric' => 'A taxa deve ser um número.',
            'fee_percentage.min' => 'A taxa deve ser maior ou igual a 0.',
            'fee_percentage.max' => 'A taxa deve ser menor ou igual a 100.',
        ]);

        // Atualiza apenas os campos enviados, preservando o empresa_id
        $validatedData = array_merge($validatedData, ['empresa_id' => $paymentMethod->empresa_id]);

        try {
            // Atualiza os dados do PaymentMethod
            $paymentMethod->update($validatedData);

            return back()->with('flash', [
                'message' => 'Método "' . $validatedData['name'] . '" atualizado com sucesso!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Em caso de erro
            return back()->with('flash', [
                'message' => 'Erro ao atualizar o método "' . $validatedData['name'] . '". Erro: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }



    /**
     * Excluir uma forma de pagamento.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Verificar se a forma de pagamento pertence à empresa do usuário
        if ($paymentMethod->empresa_id !== $empresaId) {
            return redirect()
                ->back()
                ->with('flash', [
                    'message' => 'Você não tem permissão para excluir essa forma de pagamento.',
                    'type' => 'error',
                ]);
        }

        try {
            // Deletar a forma de pagamento
            $paymentMethod->delete();

            return redirect()
                ->back()
                ->with('flash', [
                    'message' => 'Forma de pagamento excluída com sucesso.',
                    'type' => 'success',
                ]);
        } catch (\Exception $e) {
            // Caso ocorra algum erro
            return redirect()
                ->back()
                ->with('flash', [
                    'message' => 'Erro ao excluir a forma de pagamento: ' . $e->getMessage(),
                    'type' => 'error',
                ]);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    /**
     * Cria uma nova sessão de checkout para a empresa, baseado no produto do Stripe.
     */
    public function checkout(Request $request)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login'); // Redireciona para login se o usuário não estiver autenticado
        }

        // Obtém a empresa do usuário autenticado
        $empresa = Auth::user()->empresa;

        // ID do produto passado na requisição
        $idProdutoStripe = $request->id_produto_stripe;

        // Configura a chave da API do Stripe
        Stripe::setApiKey(config('cashier.secret'));

        try {
            // Obtém os detalhes do produto diretamente do Stripe
            $produtoStripe = \Stripe\Product::retrieve($idProdutoStripe);

            // Agora que você tem o produto, obtenha o preço associado a ele
            $precoStripe = \Stripe\Price::all(['product' => $idProdutoStripe]);

            // Assumindo que o primeiro preço seja o correto (ajuste se necessário)
            $preco = $precoStripe->data[0];

            // Cria a sessão de checkout com os dados do produto do Stripe
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'brl',
                            'product_data' => [
                                'name' => $produtoStripe->name, // Nome do produto
                            ],
                            'unit_amount' => $preco->unit_amount, // O valor do preço (em centavos)
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment', // Pagamento único
                'success_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
                'client_reference_id' => $empresa->id, // Salva o ID da empresa na referência
            ]);

            // Retorna o ID da sessão de checkout
            return response()->json(['id' => $checkoutSession->id]);
        } catch (\Exception $e) {
            // Em caso de erro, retorna a mensagem de erro
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Página de sucesso após o pagamento.
     */
    public function success(Request $request)
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        try {
            // Recuperar a sessão do Stripe usando o ID da sessão retornado após o pagamento
            $session = StripeSession::retrieve($request->get('session_id'));

            // Verifique se a sessão foi concluída com sucesso
            if ($session->payment_status === 'paid') {
                $empresa = Auth::user()->empresa;

                // Identificar o plano pela 'price' do Stripe
                $planoId = $session->line_items->data[0]->price->product; // Atualizado para refletir a estrutura correta
                $plano = Plano::where('id_produto_stripe', $planoId)->first();

                // Criar a assinatura para a empresa
                $empresa->newSubscription('default', $planoId)
                    ->create($session->payment_intent);

                // Retornar dados do plano como JSON
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pagamento concluído com sucesso.',
                    'plano' => [
                        'id' => $plano->id,
                        'nome' => $plano->nome,
                        'valor' => $plano->valor,
                    ],
                ]);
            }

            return response()->json(['error' => 'Pagamento não concluído.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Página de cancelamento, quando o usuário cancela o pagamento.
     */
    public function cancel()
    {
        return Inertia::render('Planos/Index', [
            'message' => 'O pagamento foi cancelado ou não foi concluído.'
        ]);
    }
}

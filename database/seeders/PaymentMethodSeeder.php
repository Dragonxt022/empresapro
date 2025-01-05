<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa_id = config('app.empresa_id');
        // Array de métodos de pagamento
        $paymentMethods = [
            ['name' => 'Dinheiro', 'type' => 'D', 'fee_percentage' => 0, 'bank_account' => null],
            ['name' => 'Crédito', 'type' => 'C', 'fee_percentage' => 0, 'bank_account' => null],
            ['name' => 'Débito', 'type' => 'T', 'fee_percentage' => 0, 'bank_account' => null],
            ['name' => 'Pix', 'type' => 'P', 'fee_percentage' => 0, 'bank_account' => null]
        ];

        // Loop para criar os métodos de pagamento
        foreach ($paymentMethods as $method) {
            PaymentMethod::create([
                'name' => $method['name'],
                'type' => $method['type'],
                'fee_percentage' => $method['fee_percentage'],
                'bank_account' => $method['bank_account'],
                'empresa_id' => $empresa_id, // Usando o ID da empresa passada como parâmetro
                'is_active' => true, // Pode definir como 'true' ou conforme necessidade
            ]);
        }
    }
}

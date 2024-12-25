<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome da forma de pagamento
            $table->enum('type', ['D', 'C', 'T', 'P']); // D = Dinheiro, C = Crédito, T = Débito, P = Pix
            $table->decimal('fee_percentage', 5, 2)->default(0); // Taxa da forma de pagamento
            $table->string('bank_account')->nullable(); // Conta bancária associada
            $table->boolean('is_active')->default(true); // Ativo ou inativo
            $table->foreignId('empresa_id') // Relacionamento com a tabela empresas
                ->constrained('empresas') // Define que empresa_id é uma FK para a tabela `empresas`
                ->onDelete('cascade'); // Exclui as formas de pagamento caso a empresa seja excluída
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

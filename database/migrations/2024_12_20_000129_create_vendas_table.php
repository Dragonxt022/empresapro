<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            // Define a chave estrangeira para a tabela caixa_movimentos
            $table->foreign('caixa_movimento_id')->references('id')->on('caixa_movimentos')->onDelete('set null');

            $table->string('cliente')->nullable(); // Cliente não identificado
            $table->string('status')->default('aberta'); // Status da venda, 'aberta' por padrão
            $table->foreignId('vendido_por')->constrained('users')->onDelete('cascade'); // Quem realizou a venda
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('acrescimo', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};

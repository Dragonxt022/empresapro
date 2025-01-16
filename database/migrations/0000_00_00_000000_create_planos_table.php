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
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('id_produto_stripe')->nullable(); // Para o caso de planos que não têm ID no Stripe
            $table->decimal('valor_mensal', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->integer('duracao_dias'); // Quantos dias dura o plano
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};

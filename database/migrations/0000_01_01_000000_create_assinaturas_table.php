<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id(); // ID da assinatura
            $table->string('nome'); // Nome da assinatura
            $table->decimal('valor_mensal', 10, 2); // Valor mensal
            $table->decimal('valor_total', 10, 2); // Valor total
            $table->integer('dias'); // Duração em dias
            $table->text('descricao')->nullable(); // Descrição da assinatura
            $table->boolean('status')->default(true); // Status (ativo/inativo)
            $table->timestamps(); // Campos created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assinaturas');
    }
};

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
        Schema::create('caixa_movimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->timestamp('data_hora_abertura');
            $table->timestamp('data_hora_fechamento')->nullable();
            $table->decimal('valor_inicial', 10, 2);
            $table->decimal('valor_final', 10, 2)->nullable();
            $table->enum('status', ['aberto', 'fechado'])->default('aberto');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixa_movimentos');
    }
};

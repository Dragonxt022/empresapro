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
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();

            $table->string('plano'); // Ex: 'Grátis', 'Básico', 'Premium', 'Enterprise'
            $table->decimal('valor', 10, 2);
            $table->date('inicio');
            $table->date('fim');
            $table->enum('status', ['ativa', 'expirada', 'cancelada'])->default('ativa');
            $table->timestamps();

            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};

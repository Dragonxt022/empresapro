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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cnpj')->unique();
            $table->string('phone')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('website')->nullable();
            $table->json('social_media')->nullable();
            $table->string('logo')->nullable();
            $table->string('fiscal_status')->default('Ativa');
            $table->enum('company_type', ['MEI', 'LTDA', 'EIRELI', 'SA', 'Outros']);
            $table->date('operating_since')->nullable();
            $table->boolean('status')->default(true);

            // Relacionamento com a assinatura
            $table->foreignId('assinatura_id')->nullable()->constrained('assinaturas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};

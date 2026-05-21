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
        Schema::create('teste_cores_respostas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone');
            $table->foreignId('cidade_id')->nullable()->constrained('cidades')->onDelete('set null');
            $table->foreignId('opcao_id')->constrained('teste_cores_opcoes')->onDelete('cascade');
            $table->string('session_id'); // Para agrupar respostas do mesmo usuário
            $table->timestamps();

            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teste_cores_respostas');
    }
};

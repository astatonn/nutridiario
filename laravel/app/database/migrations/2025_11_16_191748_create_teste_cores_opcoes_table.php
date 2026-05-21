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
        Schema::create('teste_cores_opcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pergunta_id')->constrained('teste_cores_perguntas')->onDelete('cascade');
            $table->char('letra', 1); // a, b, c, d
            $table->text('texto');
            $table->enum('cor', ['vermelho', 'amarelo', 'verde', 'azul']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teste_cores_opcoes');
    }
};

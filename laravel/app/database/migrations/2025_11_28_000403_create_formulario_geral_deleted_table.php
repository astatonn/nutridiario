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
        Schema::create('formulario_geral_deleted', function (Blueprint $table) {
            $table->id();
            $table->integer('registro_id')->unique(); // ID do registro no Supabase
            $table->timestamps();

            $table->index('registro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario_geral_deleted');
    }
};

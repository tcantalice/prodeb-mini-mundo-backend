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
        Schema::create('projeto', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->boolean('ativo');
            $table->decimal('orcamento_disponivel', 10, 2)->nullabel();
            $table->dateTimeTz('criado_em');
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projeto');
    }
};

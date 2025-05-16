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
        Schema::create('tarefa', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('descricao', 255);
            $table->dateTimeTz('data_hora_inicio')->nullable();
            $table->dateTimeTz('data_hora_fim')->nullable();
            $table->foreignId('projeto_id')->constrained('projeto');
            $table->foreignId('usuario_criador_id')->constrained('usuario');
            $table->dateTimeTz('criado_em');
            $table->timestamps();
        });

        Schema::table('tarefa', function (Blueprint $table) {
            $table->foreignId('tarefa_predecessora_id')->nullable()->constrained('tarefa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefa');
    }
};

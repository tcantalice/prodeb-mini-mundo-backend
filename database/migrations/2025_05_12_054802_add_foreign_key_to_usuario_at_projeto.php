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
        Schema::table('projeto', function (Blueprint $table) {
            $table->foreignId('usuario_criador_id')->constrained('usuario', 'id', 'usuario_criador_projeto_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projeto', function (Blueprint $table) {
            $table->dropConstrainedForeignId('usuario_criador_id');
        });
    }
};

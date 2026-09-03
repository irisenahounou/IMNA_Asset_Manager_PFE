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
        Schema::create('unites_composant', function (Blueprint $table) {
            $table->string('id')->primary(); // ex: UNT-2026-001
            $table->foreignId('id_composant')->constrained('composants', 'id_composant')->cascadeOnDelete();
            $table->string('numero_serie')->unique();
             $table->enum('etat', ['Neuf', 'Opérationnel', 'Usé', 'Défectueux'])->default('Neuf');
             $table->date('date_achat')->nullable();
             // NULL = unité en stock, non installée sur une machine.
             $table->string('id_materiel_actuel')->nullable();
           $table->foreign('id_materiel_actuel')->references('id')->on('Materiel')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unites_composant');
    }
};

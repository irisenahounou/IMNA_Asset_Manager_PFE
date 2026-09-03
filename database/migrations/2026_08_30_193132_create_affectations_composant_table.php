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
        Schema::create('affectations_composant', function (Blueprint $table) {
            $table->id();
            $table->string('id_unite');
            $table->foreign('id_unite')->references('id')->on('unites_composant')->cascadeOnDelete();
            $table->string('id_materiel');
            $table->foreign('id_materiel')->references('id')->on('Materiel')->cascadeOnDelete();
            $table->dateTime('date_installation');
            $table->dateTime('date_retrait')->nullable(); // NULL = toujours installée
              // RM-07 : état consigné au moment précis du retrait (Usé/Défectueux)
              $table->string('etat_au_retrait')->nullable();
              $table->unsignedBigInteger('id_technicien')->nullable();
            $table->foreign('id_technicien')->references('id_utilisateur')->on('Utilisateur')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations_composant');
    }
};

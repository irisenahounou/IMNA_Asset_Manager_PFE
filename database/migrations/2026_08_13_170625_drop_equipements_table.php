<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Supprime l'ancienne table equipements.
     */
    public function up(): void
    {
        Schema::dropIfExists('equipements');
    }

    /**
     * Recrée la table equipements en cas de rollback.
     */
    public function down(): void
    {

        Schema::create('equipements', function (Blueprint $table) {
            $table->id('id_equipement');
            $table->string('nom_equipement');
            $table->string('type_equipement');
            $table->string('localisation')->nullable();
            $table->enum('statut', [
                'Opérationnel',
                'En panne',
                'En maintenance'
            ])->default('Opérationnel');
            $table->timestamps();
        });
    }
};























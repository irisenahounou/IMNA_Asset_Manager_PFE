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
        // Table des composants / pièces de rechange
        Schema::create('composants', function (Blueprint $table) {
            $table->id('id_composant');
            $table->string('nom_composant');
            $table->string('type_composant'); // Ex: RAM, SSD, Disque Dur, etc.
            $table->integer('quantite_stock')->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
        });
        // Table des mouvements de stock (entrées, sorties et validation DSI)
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id('id_mouvement');
            $table->unsignedBigInteger('id_composant');
            $table->integer('quantite');
            $table->enum('type_mouvement', ['Entrée', 'Sortie']);
            $table->string('statut_validation')->default('En attente'); // En attente, Validé, Rejeté
            $table->unsignedBigInteger('id_technicien'); // Référence au technicien demandeur
            $table->timestamps();

            // Clé étrangère cohérente avec la table composants
            $table->foreign('id_composant')
            ->references('id_composant')
            ->on('composants')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
        Schema::dropIfExists('composants');
    }
};

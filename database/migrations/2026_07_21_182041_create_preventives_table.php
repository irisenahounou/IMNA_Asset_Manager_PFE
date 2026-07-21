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
        Schema::create('preventives', function (Blueprint $table){
            $table->string('id')->primary(); // Ex: PREV-2026-001
            $table->string('id_materiel'); // Le matériel concerné (anticipation d'usure)
            $table->unsignedBigInteger('id_technicien'); // Le technicien assigné par le DSI
            $table->dateTime('date_prevue'); // Date planifiée par le DSI
            $table->string('statut')->default('Planifié'); // Planifié, En cours, Terminé
            $table->text('description_alerte'); // Alerte remontée par le script Python (ex: Indice d'usure critique du disque)
            $table->text('rapport_execution')->nullable(); // Rempli par le technicien après coup
            $table->timestamps();

            $table->foreign('id_materiel')->references('id')->on('materiel')->onDelete('cascade');
            $table->foreign('id_technicien')->references('id_technicien')->on('technicien')->onDelete('cascade');
        });

        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventives');
    }
};

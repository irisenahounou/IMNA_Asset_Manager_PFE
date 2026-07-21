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
        // Ensure any existing table is removed before creating the new structure
        Schema::dropIfExists('Preventive');
        Schema::create('Preventive', function (Blueprint $table) {
            $table->string('id_preventive')->primary();
            $table->string('id_materiel'); // Cible directement le matériel
            $table->integer('frequence_jour');
            // date of last maintenance (optional)
            $table->date('date')->nullable();
            // next planned maintenance
            $table->date('prochaine_rep');

            $table->foreign('id_materiel')->references('id')->on('materiel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Preventive');
    }
};

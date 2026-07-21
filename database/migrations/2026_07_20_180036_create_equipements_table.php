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
        Schema::create('equipements', function (Blueprint $table) {
           $table->id('id_equipement');
            $table->string('nom_equipement');
            $table->string('type_equipement');
            $table->string('localisation')->nullable();
            $table->enum('statut', ['Opérationnel', 'En panne', 'En maintenance'])->default('Opérationnel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};

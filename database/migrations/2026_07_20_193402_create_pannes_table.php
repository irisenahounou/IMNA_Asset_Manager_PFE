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
        Schema::create('pannes', function (Blueprint $table) {
            $table->id('id');
            $table->string('titre', 255);
           $table->text('description');
          $table->timestamp('date_declaration')->useCurrent();
          $table->string('statut', 50)->default('Ouvert');
          $table->string('id_materiel', 50);
           $table->foreign('id_materiel')->references('id')->on('Materiel')->onDelete('cascade');
          $table->unsignedBigInteger('id_employe');

           $table->foreign('id_employe')->references('id_employe')->on('Employe')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pannes');
    }
};

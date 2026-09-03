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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id')->nullable();
            $table->string('action'); // Ex: 'Connexion', 'Validation Stock', etc.
            $table->string('ip_address', 45)->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('utilisateur_id')
            ->references('id_utilisateur')
            ->on('Utilisateur')
            ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('audits');
    }
};

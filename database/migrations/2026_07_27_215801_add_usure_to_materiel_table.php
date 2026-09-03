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
        Schema::table('Materiel', function (Blueprint $table) {
           $table->float('score_usure')->default(0)->after('etat_operationnel');
           $table->string('statut_usure')->default('Bon état')->after('score_usure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Materiel', function (Blueprint $table) {
            $table->dropColumn(['score_usure', 'statut_usure']);
        });
    }
};

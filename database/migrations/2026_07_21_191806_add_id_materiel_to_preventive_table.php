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
        Schema::table('Preventive', function (Blueprint $table) {
            
        if (!Schema::hasColumn('Preventive', 'id_materiel')){
            $table->string('id_materiel')->after('id_preventive');
            $table->foreign('id_materiel')->references('id')->on('materiel')->onDelete('cascade');
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Preventive', function (Blueprint $table) {
            $table->dropForeign(['id_materiel']);
            $table->dropColumn('id_materiel');
        });
    }
};

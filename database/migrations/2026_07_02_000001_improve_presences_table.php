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
        Schema::table('presences', function (Blueprint $table) {
            // Ajouter des colonnes pour permettre plusieurs enregistrements par séance
            $table->uuid('session_enregistrement_id')->nullable()->after('emploi_temp_id')->index();
            $table->integer('numero_enregistrement')->default(1)->after('session_enregistrement_id');
            $table->text('observations')->nullable()->after('statut');
            $table->unsignedBigInteger('formateur_id')->nullable()->after('observations');

            // Ajouter des indexes pour améliorer les performances
            $table->index(['emploi_temp_id', 'session_enregistrement_id']);
            $table->foreign('formateur_id')->references('id')->on('formateurs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropForeign(['formateur_id']);
            $table->dropColumn(['session_enregistrement_id', 'numero_enregistrement', 'observations', 'formateur_id']);
        });
    }
};

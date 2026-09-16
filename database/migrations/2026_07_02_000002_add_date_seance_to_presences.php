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
            // Ajouter la date de la séance pour grouper les enregistrements par date
            $table->date('date_seance')->nullable()->after('emploi_temp_id');

            // Ajouter des indexes pour optimiser les requêtes par séance et date
            $table->index(['emploi_temp_id', 'date_seance']);
            $table->index(['emploi_temp_id', 'date_seance', 'numero_enregistrement']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropIndex(['emploi_temp_id', 'date_seance']);
            $table->dropIndex(['emploi_temp_id', 'date_seance', 'numero_enregistrement']);
            $table->dropColumn('date_seance');
        });
    }
};

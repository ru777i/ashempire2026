<?php

use App\Models\Formateur;
use App\Models\Salle;
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
        Schema::create('emploi_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sessionn_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->dateTime('dateDebut');
            $table->foreignIdFor(Salle::class);
            $table->foreignIdFor(Formateur::class);
            $table->dateTime('dateFin');
            $table->string('statut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploi_temps');
    }
};

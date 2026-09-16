<?php

use App\Models\Formateur;
use App\Models\Formation;
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
        Schema::create('sessionns', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('capacite');
            $table->foreignIdFor(Formateur::class);
            $table->foreignIdFor(Formation::class);
            $table->foreignIdFor(Salle::class);
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->string('statut');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessionns');
    }
};

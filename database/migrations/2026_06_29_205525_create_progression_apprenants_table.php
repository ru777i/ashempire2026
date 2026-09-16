<?php

use App\Models\Inscription;
use App\Models\Module;
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
        Schema::create('progression_apprenants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inscription::class);
            $table->foreignIdFor(Module::class);
            $table->string('pourcentage');
            $table->string('statut');
            $table->string('commentaire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progression_apprenants');
    }
};

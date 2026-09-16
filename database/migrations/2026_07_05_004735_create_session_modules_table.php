<?php

use App\Models\Formateur;
use App\Models\Module;
use App\Models\Sessionn;
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
        Schema::create('session_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Module::class);
            $table->foreignIdFor(Sessionn::class);
            $table->foreignIdFor(Formateur::class);
            $table->double('volumeHoraire')->default(0);
            $table->string('statut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_modules');
    }
};

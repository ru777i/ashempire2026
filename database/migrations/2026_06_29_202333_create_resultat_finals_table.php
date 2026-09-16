<?php

use App\Models\Inscription;
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
        Schema::create('resultat_finals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inscription::class);
            $table->double('moyenneGenerale');
            $table->string('descision');
            $table->string('mention');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultat_finals');
    }
};

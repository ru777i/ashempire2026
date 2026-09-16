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
        Schema::create('resultat_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inscription::class);
            $table->foreignIdFor(Module::class);
            $table->double('moyenne');
            $table->string('statut');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultat_modules');
    }
};

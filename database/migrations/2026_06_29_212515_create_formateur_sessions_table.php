<?php

use App\Models\Formateur;
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
        Schema::create('formateur_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Module::class);
            $table->foreignIdFor(Formateur::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateur_sessions');
    }
};

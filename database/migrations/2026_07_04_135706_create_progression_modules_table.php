<?php

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
        Schema::create('progression_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Sessionn::class);
            $table->foreignIdFor(Module::class);
            $table->double('progre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progression_modules');
    }
};

<?php

use App\Models\Apprenant;
use App\Models\Salle;
use App\Models\Sessionn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Session;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id(); 
            $table->foreignIdFor(Apprenant::class);
            $table->foreignIdFor(Sessionn::class);
            $table->foreignIdFor(Salle::class);
            $table->string('dateInscription');
            $table->string('statut');
            $table->double('montantTotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};

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
        Schema::create('certificats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inscription::class)->unique();
            $table->string('numero')->unique();
            $table->string('type')->default('attestation'); // attestation | certificat
            $table->string('mention')->nullable();
            $table->double('moyenneGenerale')->nullable();
            $table->date('dateDelivrance');
            $table->string('cheminFichier')->nullable();
            $table->foreignId('genere_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificats');
    }
};

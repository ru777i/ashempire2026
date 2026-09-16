<?php

use App\Models\Inscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inscription::class);
            $table->double('montant');
            $table->string('methode')->default('espece'); // espece | mobile_money | virement | cheque
            $table->string('reference')->nullable();
            $table->date('datePaiement');
            $table->string('statut')->default('valide'); // valide | annule
            $table->text('note')->nullable();
            $table->foreignId('enregistre_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};

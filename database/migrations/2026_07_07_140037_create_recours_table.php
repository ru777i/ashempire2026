<?php

use App\Models\Formateur;
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
        Schema::create('recours', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inscription::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type');
            $table->string('objet');
            $table->text('description');

            $table->enum('statut', [
                'En attente',
                'En cours',
                'Accepté',
                'Refusé',
                'Annulé'
            ])->default('En attente');

            $table->text('reponse')->nullable();

            $table->foreignIdFor(Formateur::class)
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recours');
    }
};

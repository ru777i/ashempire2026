<?php

use App\Models\EmploiTemp;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

describe('EmploiTemp availability logic', function () {
    it('detects overlapping sessions for the same salle and date', function () {
        Schema::create('emploi_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salle_id');
            $table->string('jour');
            $table->date('date');
            $table->time('heureDebut');
            $table->time('heureFin');
            $table->timestamps();
        });

        EmploiTemp::create([
            'salle_id' => 1,
            'jour' => 'Lundi',
            'date' => '2026-07-01',
            'heureDebut' => '09:00:00',
            'heureFin' => '10:00:00',
        ]);

        expect(EmploiTemp::verifierLibre(1, '09:00', '10:00', '2026-07-01', 'Lundi'))->toBeFalse()
            ->and(EmploiTemp::verifierLibre(1, '10:00', '11:00', '2026-07-01', 'Lundi'))->toBeTrue()
            ->and(EmploiTemp::verifierLibre(2, '09:00', '10:00', '2026-07-01', 'Lundi'))->toBeTrue();

        Schema::dropIfExists('emploi_temps');
    });
});

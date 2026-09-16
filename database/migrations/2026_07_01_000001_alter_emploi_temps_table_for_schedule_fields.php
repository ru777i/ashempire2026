<?php

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
        Schema::table('emploi_temps', function (Blueprint $table) {
            if (!Schema::hasColumn('emploi_temps', 'date')) {
                $table->date('date')->nullable()->after('module_id');
            }
            if (!Schema::hasColumn('emploi_temps', 'jour')) {
                $table->string('jour')->nullable()->after('date');
            }
            if (!Schema::hasColumn('emploi_temps', 'type')) {
                $table->string('type')->nullable()->after('jour');
            }
            if (!Schema::hasColumn('emploi_temps', 'heureDebut')) {
                $table->time('heureDebut')->nullable()->after('type');
            }
            if (!Schema::hasColumn('emploi_temps', 'heureFin')) {
                $table->time('heureFin')->nullable()->after('heureDebut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emploi_temps', function (Blueprint $table) {
            $table->dropColumn(['date', 'jour', 'type', 'heureDebut', 'heureFin']);
        });
    }
};

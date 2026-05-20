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
        Schema::table(
            'material_progress',
            function (Blueprint $table) {

                $table->integer(
                    'trigger_score'
                )
                ->default(0)
                ->after(
                    'trigger_answer'
                );
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'material_progress',
            function (Blueprint $table) {

                $table->dropColumn(
                    'trigger_score'
                );
            }
        );
    }
};
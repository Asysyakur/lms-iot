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

                $table->timestamp(
                    'started_at'
                )->nullable();

                $table->timestamp(
                    'last_activity_at'
                )->nullable();

                $table->unsignedInteger(
                    'duration_seconds'
                )->default(0);

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

                $table->dropColumn([
                    'started_at',
                    'last_activity_at',
                    'duration_seconds',
                ]);

            }
        );
    }
};
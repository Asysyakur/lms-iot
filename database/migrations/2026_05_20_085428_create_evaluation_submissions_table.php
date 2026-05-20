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
        Schema::create(
            'evaluation_submissions',
            function (
                Blueprint $table
            ) {

                $table->id();

                $table
                    ->foreignId(
                        'user_id'
                    )
                    ->constrained()
                    ->cascadeOnDelete();

                $table
                    ->foreignId(
                        'meeting_id'
                    )
                    ->constrained()
                    ->cascadeOnDelete();

                $table->json(
                    'answers'
                );

                $table->timestamp(
                    'submitted_at'
                )->nullable();

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'evaluation_submissions'
        );
    }
};
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
        Schema::create('material_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('meeting_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('reading_progress')
                ->default(0);

            $table->text('trigger_answer')
                ->nullable();

            $table->json('reflection_answers')
                ->nullable();

            $table->boolean('reflection_completed')
                ->default(false);

            $table->timestamps();

            $table->unique([
                'user_id',
                'meeting_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_progress');
    }
};

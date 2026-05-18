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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meeting_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->string('description')->nullable();

            $table->string('pdf_file')->nullable();

            $table->string('video_url')->nullable();

            $table->text('trigger_question')->nullable();

            $table->text('reflection_question')->nullable();

            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

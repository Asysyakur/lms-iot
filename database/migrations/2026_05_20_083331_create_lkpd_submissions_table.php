<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'lkpd_submissions',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('meeting_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->string('file_path');

                $table->string('original_name');

                $table->string('mime_type');

                $table->unsignedBigInteger('file_size');

                $table->timestamp('submitted_at')
                    ->nullable();

                $table->timestamps();

                $table->unique([
                    'user_id',
                    'meeting_id',
                ]);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'lkpd_submissions'
        );
    }
};
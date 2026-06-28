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
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->index('assessment_id', 'assessment_results_assessment_id_idx');
            $table->index('student_id', 'assessment_results_student_id_idx');

            $table->dropUnique(
                'assessment_results_assessment_id_student_id_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->unique([
                'assessment_id',
                'student_id',
            ]);
        });
    }
};

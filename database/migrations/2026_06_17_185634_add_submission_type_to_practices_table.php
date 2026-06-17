<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'practices',
            function (
                Blueprint $table
            ) {

                $table->enum(
                    'submission_type',
                    [
                        'link',
                        'text',
                        'both'
                    ]
                )
                ->default('link')
                ->after('makecode_url');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'practices',
            function (
                Blueprint $table
            ) {

                $table->dropColumn(
                    'submission_type'
                );
            }
        );
    }
};
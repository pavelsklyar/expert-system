<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('next_questions', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->constrained('questions');
            $table->foreignUuid('next_question_id')->constrained('questions');
            $table->jsonb('condition')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('next_questions');
    }
};

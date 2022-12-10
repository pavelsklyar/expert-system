<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conditions', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rule_id')->constrained('rules');
            $table->foreignUuid('question_id')->constrained('questions');
            $table->string('operator');
            $table->string('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};

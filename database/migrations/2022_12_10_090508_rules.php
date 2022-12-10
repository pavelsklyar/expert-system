<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rules', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('entity');
            $table->string('field');
            $table->string('operator');
            $table->jsonb('values');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};

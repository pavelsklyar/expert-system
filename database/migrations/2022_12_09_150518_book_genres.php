<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_genres', static function (Blueprint $table) {
            $table->foreignUuid('book_id')->constrained('books');
            $table->foreignUuid('genre_id')->constrained('genres');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_genres');
    }
};

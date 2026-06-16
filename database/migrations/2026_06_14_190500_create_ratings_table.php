<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('photo_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('rating');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['photo_id', 'user_id'], 'unique_rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};

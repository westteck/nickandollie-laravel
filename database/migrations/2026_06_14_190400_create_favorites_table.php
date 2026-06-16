<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('photo_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['photo_id', 'user_id'], 'unique_favorite');
            $table->index(['user_id', 'photo_id'], 'idx_user_photo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};

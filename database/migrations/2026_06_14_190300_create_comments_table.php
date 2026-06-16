<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('photo_id');
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            $table->index('photo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

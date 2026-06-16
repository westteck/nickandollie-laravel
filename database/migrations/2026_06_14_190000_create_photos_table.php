<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename')->nullable();
            $table->string('thumb_filename')->default('');
            $table->string('print_filename')->default('');
            $table->unsignedBigInteger('uploader_id')->nullable();
            $table->text('caption')->nullable();
            $table->unsignedInteger('photo_number')->default(0);
            $table->integer('likes')->default(0);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->index('uploader_id');
            $table->index('uploaded_at');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};

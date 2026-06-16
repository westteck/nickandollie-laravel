<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contest_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contest_id');
            $table->unsignedBigInteger('photo_id');
            $table->timestamp('submitted_at')->useCurrent();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('votes')->default(0);
            $table->index('contest_id');
            $table->index('photo_id');
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade');
            $table->foreign('photo_id')->references('id')->on('photos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contest_entries');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 64);
            $table->string('title', 255)->default('');
            $table->text('content')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unique('page_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_pages');
    }
};

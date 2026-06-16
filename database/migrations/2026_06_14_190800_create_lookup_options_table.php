<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lookup_options', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50);
            $table->string('value', 100);
            $table->string('label', 255);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unique(['category', 'value'], 'category_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lookup_options');
    }
};

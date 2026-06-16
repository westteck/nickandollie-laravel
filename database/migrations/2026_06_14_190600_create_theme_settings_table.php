<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary', 7)->default('#8b7355');
            $table->string('secondary', 7)->default('#d4c4b0');
            $table->string('accent', 7)->default('#c9a86c');
            $table->string('background', 7)->default('#faf8f5');
            $table->string('text', 7)->default('#3d3530');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};

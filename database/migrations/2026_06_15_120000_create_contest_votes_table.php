<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create contest_votes table for tracking contest-specific votes
        if (!Schema::hasTable('contest_votes')) {
            Schema::create('contest_votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contest_entry_id')->constrained('contest_entries')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['contest_entry_id', 'user_id']);
            });
        }

        // Ensure contest_entries has a votes column for caching
        if (!Schema::hasColumn('contest_entries', 'votes')) {
            Schema::table('contest_entries', function (Blueprint $table) {
                $table->unsignedInteger('votes')->default(0)->after('contest_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contest_votes');
        if (Schema::hasColumn('contest_entries', 'votes')) {
            Schema::table('contest_entries', function (Blueprint $table) {
                $table->dropColumn('votes');
            });
        }
    }
};

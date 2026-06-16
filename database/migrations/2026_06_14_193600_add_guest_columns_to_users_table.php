<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add guest/contact columns to users that were never captured
     * in a migration. Prod has these from a manual legacy import;
     * tests on sqlite need them too.
     *
     * Uses hasColumn() so this is safe to run on both states.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'guest_name')) {
                $table->string('guest_name', 255)->nullable()->before('email');
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('guest_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'connection')) {
                $table->enum('connection', ['nick', 'ollie', 'both'])->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'core_group')) {
                $table->enum('core_group', ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community'])->nullable()->after('connection');
            }
            if (!Schema::hasColumn('users', 'specific_relationship')) {
                $table->string('specific_relationship', 255)->nullable()->after('core_group');
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 255)->nullable()->unique()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'profile_pic')) {
                $table->text('profile_pic')->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'rsvp_status')) {
                $table->enum('rsvp_status', ['pending', 'confirmed', 'declined'])->default('pending')->after('phone');
            }
            if (!Schema::hasColumn('users', 'connection_id')) {
                $table->unsignedInteger('connection_id')->nullable()->after('rsvp_status');
            }
            if (!Schema::hasColumn('users', 'core_group_id')) {
                $table->unsignedInteger('core_group_id')->nullable()->after('connection_id');
            }
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['admin', 'partner', 'user'])->default('user')->after('remember_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists([
                'guest_name', 'first_name', 'last_name', 'connection', 'core_group',
                'specific_relationship', 'username', 'profile_pic', 'phone',
                'rsvp_status', 'connection_id', 'core_group_id', 'user_type',
            ]);
        });
    }
};

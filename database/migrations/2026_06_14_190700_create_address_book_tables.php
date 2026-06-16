<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address_book', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('entry_name', 100)->default('');
            $table->string('first_name', 100)->default('');
            $table->string('family_connection', 50)->default('');
            $table->string('address', 200)->default('');
            $table->string('city', 100)->default('');
            $table->string('state', 50)->default('');
            $table->string('zip', 20)->default('');
            $table->string('email', 100)->default('');
            $table->string('phone', 30)->default('');
            $table->string('mobile', 30)->default('');
            $table->text('notes');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->boolean('show_in_phonebook')->default(true);
            $table->index('user_id');
        });

        Schema::create('address_book_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_book_id');
            $table->string('contact_name', 100)->default('');
            $table->string('first_name', 100)->default('');
            $table->string('family_connection', 50)->default('');
            $table->date('birthday')->nullable();
            $table->string('notes', 255)->default('');
            $table->timestamp('created_at')->useCurrent();
            $table->index('address_book_id');
            $table->foreign('address_book_id')->references('id')->on('address_book')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address_book_contacts');
        Schema::dropIfExists('address_book');
    }
};

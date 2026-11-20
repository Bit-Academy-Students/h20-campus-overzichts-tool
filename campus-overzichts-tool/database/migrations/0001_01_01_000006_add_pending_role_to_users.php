<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pending', 'municipality', 'admin'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        // Pending users bestaan niet meer in de oude enum, dus eerst omzetten.
        DB::table('users')->where('role', 'pending')->update(['role' => 'municipality']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['municipality', 'admin'])->default('municipality')->change();
        });
    }
};

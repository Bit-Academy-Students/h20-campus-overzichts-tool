<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment_claims', function (Blueprint $table) {
            $table->dropForeign('fk_claims_user');
            $table->foreign('user_id', 'fk_claims_user')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assignment_claims', function (Blueprint $table) {
            $table->dropForeign('fk_claims_user');
            $table->foreign('user_id', 'fk_claims_user')->references('id')->on('users');
        });
    }
};

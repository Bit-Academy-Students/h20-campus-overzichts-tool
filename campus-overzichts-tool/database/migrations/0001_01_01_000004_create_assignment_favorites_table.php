<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_favorites', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('assignment_id')->index('idx_assignment_favorites_assignment');
            $table->primary(['user_id', 'assignment_id']);

            $table->foreign('user_id', 'fk_assignment_favorites_user')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('assignment_id', 'fk_assignment_favorites_assignment')->references('id')->on('assignments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_favorites');
    }
};

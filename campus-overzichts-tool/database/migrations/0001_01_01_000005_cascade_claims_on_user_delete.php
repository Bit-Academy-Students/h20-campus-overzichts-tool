<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->recreateUserForeignKey(cascade: true);
    }

    public function down(): void
    {
        $this->recreateUserForeignKey(cascade: false);
    }

    private function recreateUserForeignKey(bool $cascade): void
    {
        Schema::table('assignment_claims', function (Blueprint $table) use ($cascade) {
            // SQLite kent geen foreign keys op naam, MySQL wel.
            $table->dropForeign(DB::getDriverName() === 'sqlite' ? ['user_id'] : 'fk_claims_user');

            $foreign = $table->foreign('user_id', 'fk_claims_user')->references('id')->on('users');

            if ($cascade) {
                $foreign->cascadeOnDelete();
            }
        });
    }
};

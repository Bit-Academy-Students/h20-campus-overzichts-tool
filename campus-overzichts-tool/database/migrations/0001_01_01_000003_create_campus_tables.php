<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opdrachtnemers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('opdrachtgevers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('contactpersonen', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opdrachtgever_id')->index('idx_contactpersonen_opdrachtgever');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('function', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('opdrachtgever_id', 'fk_contactpersonen_opdrachtgever')->references('id')->on('opdrachtgevers')->cascadeOnDelete();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('opdrachtgever_id')->nullable()->index('idx_assignments_opdrachtgever');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('source_url', 500)->nullable()->unique('uq_assignments_source_url');
            $table->date('posted_at')->nullable();
            $table->timestamp('scraped_at')->useCurrent();

            $table->foreign('opdrachtgever_id', 'fk_assignments_opdrachtgever')->references('id')->on('opdrachtgevers')->nullOnDelete();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique('uq_tags_name');
        });

        Schema::create('keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word', 50)->unique('uq_keywords_word');
        });

        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('url', 500)->unique('uq_sources_url');
            $table->boolean('active')->default(true);
        });

        Schema::create('assignment_claims', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id')->unique('uq_claims_assignment');
            $table->unsignedInteger('user_id')->index('idx_claims_user');
            $table->unsignedInteger('opdrachtnemer_id')->nullable()->index('idx_claims_opdrachtnemer');
            $table->enum('status', ['claimed', 'assigned', 'completed'])->default('claimed');
            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamp('assigned_at')->nullable();

            $table->foreign('assignment_id', 'fk_claims_assignment')->references('id')->on('assignments')->cascadeOnDelete();
            $table->foreign('user_id', 'fk_claims_user')->references('id')->on('users');
            $table->foreign('opdrachtnemer_id', 'fk_claims_opdrachtnemer')->references('id')->on('opdrachtnemers')->nullOnDelete();
        });

        Schema::create('opdrachtnemer_tags', function (Blueprint $table) {
            $table->unsignedInteger('opdrachtnemer_id');
            $table->unsignedInteger('tag_id')->index('idx_opdrachtnemer_tags_tag');
            $table->primary(['opdrachtnemer_id', 'tag_id']);

            $table->foreign('opdrachtnemer_id', 'fk_opdrachtnemer_tags_opdrachtnemer')->references('id')->on('opdrachtnemers')->cascadeOnDelete();
            $table->foreign('tag_id', 'fk_opdrachtnemer_tags_tag')->references('id')->on('tags')->cascadeOnDelete();
        });

        Schema::create('assignment_tags', function (Blueprint $table) {
            $table->unsignedInteger('assignment_id');
            $table->unsignedInteger('tag_id')->index('idx_assignment_tags_tag');
            $table->primary(['assignment_id', 'tag_id']);

            $table->foreign('assignment_id', 'fk_assignment_tags_assignment')->references('id')->on('assignments')->cascadeOnDelete();
            $table->foreign('tag_id', 'fk_assignment_tags_tag')->references('id')->on('tags')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_tags');
        Schema::dropIfExists('opdrachtnemer_tags');
        Schema::dropIfExists('assignment_claims');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('contactpersonen');
        Schema::dropIfExists('opdrachtgevers');
        Schema::dropIfExists('opdrachtnemers');
    }
};

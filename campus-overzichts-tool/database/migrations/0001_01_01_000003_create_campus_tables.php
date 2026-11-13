<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
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

        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('source_url', 500)->nullable()->unique('uq_assignments_source_url');
            $table->date('posted_at')->nullable();
            $table->timestamp('scraped_at')->useCurrent();
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
            $table->unsignedInteger('company_id')->nullable()->index('idx_claims_company');
            $table->enum('status', ['claimed', 'assigned', 'completed'])->default('claimed');
            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamp('assigned_at')->nullable();

            $table->foreign('assignment_id', 'fk_claims_assignment')->references('id')->on('assignments')->cascadeOnDelete();
            $table->foreign('user_id', 'fk_claims_user')->references('id')->on('users');
            $table->foreign('company_id', 'fk_claims_company')->references('id')->on('companies')->nullOnDelete();
        });

        Schema::create('company_tags', function (Blueprint $table) {
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('tag_id')->index('idx_company_tags_tag');
            $table->primary(['company_id', 'tag_id']);

            $table->foreign('company_id', 'fk_company_tags_company')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('tag_id', 'fk_company_tags_tag')->references('id')->on('tags')->cascadeOnDelete();
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
        Schema::dropIfExists('company_tags');
        Schema::dropIfExists('assignment_claims');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('companies');
    }
};

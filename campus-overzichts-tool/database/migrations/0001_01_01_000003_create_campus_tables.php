<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contractors', function (Blueprint $table) {
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

        Schema::create('clients', function (Blueprint $table) {
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

        // Een contactpersoon hoort bij precies één client OF één contractor.
        // MySQL staat geen CHECK toe op kolommen met een CASCADE foreign key,
        // daarom wordt "precies één van de twee" in het model ContactPerson afgedwongen.
        Schema::create('contact_persons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->nullable()->index('idx_contact_persons_client');
            $table->unsignedInteger('contractor_id')->nullable()->index('idx_contact_persons_contractor');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('job_title', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('client_id', 'fk_contact_persons_client')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('contractor_id', 'fk_contact_persons_contractor')->references('id')->on('contractors')->cascadeOnDelete();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->nullable()->index('idx_assignments_client');
            $table->unsignedInteger('contact_person_id')->nullable()->index('idx_assignments_contact_person');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('source_url', 500)->nullable()->unique('uq_assignments_source_url');
            $table->enum('status', ['new', 'in_review', 'accepted', 'rejected', 'assigned'])->default('new');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->date('posted_at')->nullable();
            $table->timestamp('scraped_at')->useCurrent();

            $table->foreign('client_id', 'fk_assignments_client')->references('id')->on('clients')->nullOnDelete();
            $table->foreign('contact_person_id', 'fk_assignments_contact_person')->references('id')->on('contact_persons')->nullOnDelete();
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
            $table->unsignedInteger('contractor_id')->nullable()->index('idx_claims_contractor');
            $table->enum('status', ['claimed', 'assigned', 'completed'])->default('claimed');
            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamp('assigned_at')->nullable();

            $table->foreign('assignment_id', 'fk_claims_assignment')->references('id')->on('assignments')->cascadeOnDelete();
            $table->foreign('user_id', 'fk_claims_user')->references('id')->on('users');
            $table->foreign('contractor_id', 'fk_claims_contractor')->references('id')->on('contractors')->nullOnDelete();
        });

        Schema::create('contractor_tags', function (Blueprint $table) {
            $table->unsignedInteger('contractor_id');
            $table->unsignedInteger('tag_id')->index('idx_contractor_tags_tag');
            $table->primary(['contractor_id', 'tag_id']);

            $table->foreign('contractor_id', 'fk_contractor_tags_contractor')->references('id')->on('contractors')->cascadeOnDelete();
            $table->foreign('tag_id', 'fk_contractor_tags_tag')->references('id')->on('tags')->cascadeOnDelete();
        });

        Schema::create('client_tags', function (Blueprint $table) {
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('tag_id')->index('idx_client_tags_tag');
            $table->primary(['client_id', 'tag_id']);

            $table->foreign('client_id', 'fk_client_tags_client')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('tag_id', 'fk_client_tags_tag')->references('id')->on('tags')->cascadeOnDelete();
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
        Schema::dropIfExists('client_tags');
        Schema::dropIfExists('contractor_tags');
        Schema::dropIfExists('assignment_claims');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('contact_persons');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('contractors');
    }
};

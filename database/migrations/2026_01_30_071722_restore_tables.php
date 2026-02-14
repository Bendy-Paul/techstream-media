<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'company', 'user'])->default('user');
            $table->string('avatar_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Locations (Countries, States, Cities)
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('sortname', 3);
            $table->string('name', 150);
            $table->integer('phonecode');
            $table->timestamps();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 25)->nullable();
            $table->string('state_code', 10)->nullable();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('city_code', 10)->default('');
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Taxonomies (Categories, Tags, Stacks)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('type', 100);
            $table->string('icon_class', 50)->nullable();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
        });

        Schema::create('stacks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon_class', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamps();
        });

        // 4. Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website_url')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->integer('state_id')->nullable(); // Optional constraint if needed
            $table->integer('country_id')->nullable(); // Optional constraint if needed
            $table->string('team_size', 50)->nullable();
            $table->year('year_founded')->nullable();
            $table->decimal('starting_cost', 15, 2)->nullable();
            $table->string('currency', 3)->default('NGN');
            $table->enum('subscription_tier', ['free', 'silver', 'gold'])->default('free');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->json('social_links')->nullable();
            $table->json('profile_stats')->nullable();
            $table->json('stack_ids')->nullable(); // Legacy JSON column, pivot table preferred
            $table->timestamps();
        });

        // 5. Articles
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('featured_image_url')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('views')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        // 6. Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('event_type', ['conference', 'hackathon', 'meetup', 'workshop', 'webinar']);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('location_name')->nullable();
            $table->integer('city_id')->nullable();
            $table->boolean('is_virtual')->default(false);
            $table->decimal('price', 15, 2)->default(0.00);
            $table->string('ticket_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('banner_image_url')->nullable();
            $table->json('social_links')->nullable();
            $table->json('community_links')->nullable();
            $table->timestamps();
        });

        // 7. Jobs
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('company_name');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->enum('job_type', ['Full-time', 'Part-time', 'Contract', 'Internship', 'Freelance']);
            $table->string('location');
            $table->boolean('is_remote')->default(false);
            $table->string('salary_range', 100)->nullable();
            $table->enum('experience_level', ['Entry Level', 'Mid Level', 'Senior', 'Executive'])->default('Mid Level');
            $table->string('apply_link')->nullable();
            $table->text('skills')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });

        // 8. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('item_type', 20)->nullable()->comment('company, event or news');
            // Note: The SQL dump specifically constraints item_id to companies(id)
            $table->foreignId('item_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // Check constraint (1-5) is usually handled in application logic
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        // 9. Company Related Tables
        Schema::create('company_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->date('visit_date');
            $table->integer('visit_count')->default(1);
            $table->unique(['company_id', 'visit_date'], 'unique_daily_visit');
        });

        Schema::create('company_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('image_url');
            $table->string('caption')->nullable();
        });

        Schema::create('company_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->text('address');
            $table->boolean('is_headquarters')->default(false);
        });

        Schema::create('company_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
        });

        // 10. Event Related Tables
        Schema::create('event_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('image_url');
        });

        Schema::create('event_speakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('image_url')->nullable();
        });

        // 11. Pivot Tables

        // Articles <-> Categories
        Schema::create('article_categories', function (Blueprint $table) {
            $table->foreignId('article_id')->nullable(); // SQL allows null, but usually should constrain
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
        });

        // Articles <-> Companies
        Schema::create('article_companies', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->primary(['article_id', 'company_id']);
        });

        // Articles <-> Events
        Schema::create('article_events', function (Blueprint $table) {
            $table->bigInteger('article_id'); // SQL dump did not have FK constraints on this pivot
            $table->bigInteger('event_id');
            // Adding index manually since constraints were missing in original dump
            $table->index('article_id');
            $table->index('event_id');
        });

        // Articles <-> Tags
        Schema::create('article_tags', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['article_id', 'tag_id']);
        });

        // Companies <-> Categories
        Schema::create('company_categories', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->primary(['company_id', 'category_id']);
        });

        // Companies <-> Stacks (Pivot table found in dump)
        Schema::create('company_stack', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('stack_id');
            $table->timestamps();
        });

        // Category <-> Event
        Schema::create('category_event', function (Blueprint $table) {
            $table->char('category_id', 36);
            $table->char('event_id', 36);
            $table->primary(['category_id', 'event_id']);
            $table->timestamps();
        });

        // Events <-> Tags
        Schema::create('event_tags', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['event_id', 'tag_id']);
        });

        // Cache Table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order of dependency
        Schema::dropIfExists('cache');
        Schema::dropIfExists('event_tags');
        Schema::dropIfExists('category_event');
        Schema::dropIfExists('company_stack');
        Schema::dropIfExists('company_categories');
        Schema::dropIfExists('article_tags');
        Schema::dropIfExists('article_events');
        Schema::dropIfExists('article_companies');
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('event_speakers');
        Schema::dropIfExists('event_galleries');
        Schema::dropIfExists('company_projects');
        Schema::dropIfExists('company_locations');
        Schema::dropIfExists('company_galleries');
        Schema::dropIfExists('company_analytics');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('events');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('stacks');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('users');
    }
};
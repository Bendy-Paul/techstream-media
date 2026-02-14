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
        Schema::table('jobs', function (Blueprint $table) {
            //
            $table->text('summary');
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            // $table->string('experience_level');
            $table->string('education_level')->nullable();
            // $table->string('salary_range')->nullable();
            $table->enum('application_type', ['smart_apply', 'external'])->default('smart_apply');
            // $table->string('apply_link')->nullable();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            // $table->date('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
};

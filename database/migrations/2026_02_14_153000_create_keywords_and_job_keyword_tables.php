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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            // Unique index prevents duplicate "Laravel" entries
            $table->string('name')->unique(); 
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('job_keyword', function (Blueprint $table) {
            $table->id();
            // Foreign keys to connect Job and Keyword 
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('keyword_id')->constrained()->onDelete('cascade');
            
            // Weighting: 1=Nice to have, 2=Preferred, 3=Required
            $table->unsignedTinyInteger('weight')->default(1); 

            // Composite index makes matching queries significantly faster
            $table->index(['job_id', 'keyword_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_keyword');
        Schema::dropIfExists('keywords');
    }
};

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
    Schema::create('job_tool', function (Blueprint $table) {
        $table->id();
        
        // This automatically assumes unsignedBigInteger and matches 'id' on 'jobs' table
        $table->foreignId('job_id')->constrained()->onDelete('cascade');
        
        // This automatically assumes unsignedBigInteger and matches 'id' on 'stacks' table
        // Ensure the table name is exactly 'stacks'
        $table->foreignId('stack_id')->constrained()->onDelete('cascade');
        
        $table->timestamps(); // Optional, but usually helpful
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_tool');
    }
};

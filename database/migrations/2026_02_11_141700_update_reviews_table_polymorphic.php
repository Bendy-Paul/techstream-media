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
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            // Note: The constraint name implies it's on company_id but the column is item_id
            // Based on previous file view, it was constrained to companies.
            $table->dropForeign(['item_id']); 
            
            // We need to change item_type to be a string that can hold the class name
            // Previously it was string(20) which might be too short for full class names
            $table->string('item_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Re-add the foreign key constraint (assuming it was to companies)
             $table->foreign('item_id')->references('id')->on('companies')->onDelete('cascade');
             $table->string('item_type', 20)->change();
        });
    }
};

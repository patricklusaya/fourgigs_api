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
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']); // Adjust column name if necessary

            // Add the new foreign key that references the job_listing table
            $table->foreign('job_id')->references('id')->on('job_listing')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']); 

            // Add the old foreign key back if needed (pointing to the jobs table)
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }
};

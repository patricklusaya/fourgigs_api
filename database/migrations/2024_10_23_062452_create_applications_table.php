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
        Schema::create('applications', function (Blueprint $table) {
            $table->id(); 
            $table->bigInteger('job_id')->unsigned(); 
            $table->bigInteger('user_id')->unsigned(); 
            $table->text('cover_letter'); 
            $table->string('resume', 255); 
            $table->string('status', 50); 
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('applications');
    }
};

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
        Schema::create('students', function (Blueprint $table) {
            $table->string('student_id', 15)->primary();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('password_hash', 255);
            $table->char('course', 10);
            $table->enum('year_level', ['1st Year','2nd Year','3rd Year','4th Year','5th Year']);
            $table->date('birthday');
            $table->text('bio')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

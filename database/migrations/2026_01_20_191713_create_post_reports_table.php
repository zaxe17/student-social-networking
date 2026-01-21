<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_reports', function (Blueprint $table) {
            $table->id();

            // posts.post_id is usually BIGINT, so keep this:
            $table->unsignedBigInteger('post_id');

            // student_id in your system is a string like "2023-04214-MN-0"
            // so reported_by must also be string
            $table->string('reported_by');

            $table->string('reason');
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('post_id')
                ->references('post_id')
                ->on('posts')
                ->onDelete('cascade');

            // IMPORTANT: this only works IF students.student_id is also string/varchar
            $table->foreign('reported_by')
                ->references('student_id')
                ->on('students')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reports');
    }
};

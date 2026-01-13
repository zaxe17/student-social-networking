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
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->enum('category_name', [
                'Announcements',
                'Events',
                'Discussions',
                'Help',
                'Achievements',
                'Lost & Found',
                'Marketplace',
                'Clubs & Organizations',
                'Entertainment',
                'Miscellaneous'
            ])->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_categories');
    }
};

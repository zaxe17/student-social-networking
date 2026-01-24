<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Announcements',
            'Events',
            'Discussions',
            'Help',
            'Achievements',
            'Lost & Found',
            'Marketplace',
            'Clubs & Organizations',
            'Entertainment',
            'Miscellaneous',
        ];

        foreach ($categories as $category) {
            PostCategory::create([
                'category_name' => $category
            ]);
        }
    }
}
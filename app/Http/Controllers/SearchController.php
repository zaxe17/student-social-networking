<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Post;

class SearchController extends Controller
{
    // Autocomplete endpoint
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Recommend profiles
        $profiles = Student::where('name', 'like', "%{$query}%")->limit(5)->get();

        // Recommend posts (limit and show short content)
        $posts = Post::where('content', 'like', "%{$query}%")
                     ->limit(5)
                     ->get()
                     ->map(function($post) {
                         $post->short_content = strlen($post->content) > 50
                             ? substr($post->content, 0, 50) . '...'
                             : $post->content;
                         return $post;
                     });

        return response()->json([
            'profiles' => $profiles,
            'posts' => $posts,
        ]);
    }

    // Full search results page
    public function searchResults(Request $request)
    {
        $query = $request->input('q');

        $posts = Post::where('content', 'like', "%{$query}%")
            ->with(['author', 'category'])
            ->latest()
            ->get();

        return view('page.results', compact('posts', 'query'));
    }
}
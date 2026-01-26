<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Post;
use App\Models\PostCategory;

class SearchController extends Controller
{
    // ----------------------
    // Autocomplete for profiles only
    // ----------------------
    public function search(Request $request)
    {
        $query = $request->input('q');

        // If query is empty, return empty profiles array
        if (!$query) {
            return response()->json(['profiles' => []]);
        }

        // Fetch profiles only (first_name OR last_name match)
        $profiles = Student::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($profile) {
                return [
                    'student_id' => $profile->student_id,
                    'name' => $profile->first_name . ' ' . $profile->last_name,
                ];
            });

        return response()->json([
            'profiles' => $profiles,
        ]);
    }

    // ----------------------
    // Full search results page
    // ----------------------
    public function searchResults(Request $request)
    {
        $query = $request->input('q');

        $studentId = $request->session()->get('student_id');
        $student = $studentId ? Student::find($studentId) : null;

        $posts = Post::with(['author', 'category', 'comments.author', 'likes'])
            ->withCount(['comments', 'likes'])
            ->where('content', 'LIKE', "%{$query}%")
            ->latest()
            ->get();

        $categories = PostCategory::orderBy('category_name')->get();

        return view('page.results', [
            'posts' => $posts,
            'categories' => $categories,
            'query' => $query,
            'student' => $student,
            'loggedInStudent' => $student,
        ]);
    }
}

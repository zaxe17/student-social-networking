<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PostReport;
use App\Models\Post;

class AdminAuthController extends Controller
{
    private string $fixedUsername = 'PupIskonnectAdmin';
    // hash for: adminkonnect123
    private string $passwordHash = '$2y$12$8ir4PPuozvSTDG/TolLNY.KfaJGgG0QGB5t/bLdTjwKxcIpVoEHI6';

    public function showLogin(Request $request)
    {
        // already logged in → go to dashboard
        if ($request->session()->get('is_admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (
            $request->username !== $this->fixedUsername ||
            !Hash::check($request->password, $this->passwordHash)
        ) {
            return back()->withErrors([
                'login' => 'Invalid admin credentials.',
            ]);
        }

        // mark admin as logged in
        $request->session()->put('is_admin', true);
        return redirect()->route('admin.dashboard');
    }

    public function dashboard(Request $request)
    {
        if (!$request->session()->get('is_admin')) {
            return redirect()->route('admin.login');
        }

        // Load reports grouped by post
        $reports = PostReport::with(['post.author'])
            ->get()
            ->groupBy('post_id') // Group reports by post
            ->sortByDesc(fn($postReports) => $postReports->count()); // Sort descending by count

        return view('admin.dashboard', compact('reports'));
    }

    /**
     * Delete a specific report (keep the post)
     */
    public function deleteReport(Request $request, $id)
    {
        if (!$request->session()->get('is_admin')) {
            return redirect()->route('admin.login');
        }

        $report = PostReport::findOrFail($id);
        $report->delete();

        return back()->with('success', 'Report deleted successfully.');
    }

    /**
     * Delete the post associated with a report
     */
    public function deletePost(Request $request, $id)
    {
        if (!$request->session()->get('is_admin')) {
            return redirect()->route('admin.login');
        }

        $report = PostReport::findOrFail($id);
        $post = Post::where('post_id', $report->post_id)->first();

        if ($post) {
            // Soft delete the post (this will also hide it from users)
            $post->delete();

            // Optionally delete all reports for this post
            PostReport::where('post_id', $report->post_id)->delete();

            return back()->with('success', 'Post and all associated reports deleted successfully.');
        }

        return back()->with('error', 'Post not found.');
    }

    public function logout(Request $request)
    {
        // Only remove admin session, keep student session intact
        $request->session()->forget('is_admin');
        // Don't invalidate() or flush() here
        return redirect()->route('admin.login');
    }
}
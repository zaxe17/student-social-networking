<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\PostReport;

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

    // ✅ real data from DB
    $reports = PostReport::orderBy('created_at', 'desc')->get();

    return view('admin.dashboard', compact('reports'));
}

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

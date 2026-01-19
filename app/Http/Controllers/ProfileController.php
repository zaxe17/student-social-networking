<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PostCategory;

class ProfileController extends Controller
{

public function index()
{
    $posts = \App\Models\Post::with(['author', 'category', 'comments.author'])
        ->withCount(['likes', 'comments'])
        ->where('student_id', auth()->user()->student_id)
        ->latest('created_at')
        ->get();

    $categories = PostCategory::orderBy('category_name')->get();

    return view('page.profile', compact('posts', 'categories'));
}


    public function update(Request $request)
    {
       $request->validate([
    'first_name' => ['required', 'string'],
    'last_name'  => ['required', 'string'],
    'bio'        => ['nullable', 'string'],
    'facebook'   => ['nullable', 'url'],
    'instagram'  => ['nullable', 'url'],
    'linkedin'   => ['nullable', 'url'],
    'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
]);

Auth::user()->update([
    'first_name' => $request->first_name,
    'last_name'  => $request->last_name,
    'bio'        => $request->bio,
    'facebook'   => $request->facebook,
    'instagram'  => $request->instagram,
    'linkedin'   => $request->linkedin,
    'photo'      => $request->photo ? $request->file('photo')->store('profile_photos', 'public') : Auth::user()->photo,
]);


        return back()->with('success', 'Profile updated!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        $student = Auth::user();

        if (!Hash::check($request->current_password, $student->password_hash)) {
            return back()->with('error', 'Wrong current password.');
        }

        $student->update([
            'password_hash' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed!');
    }
}

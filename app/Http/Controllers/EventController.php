<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // /events page (show upcoming only)
    public function index()
    {
        $events = Event::query()
            ->whereRaw("TIMESTAMP(event_date, COALESCE(end_time, '23:59:59')) >= ?", [now()])
            ->orderBy('event_date', 'asc')
            ->paginate(9);

        return view('page.events', compact('events'));
    }

    // store event (auto post)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:255'],
            'registration_url' => ['nullable', 'url', 'max:500'],
            'header' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // normalize URL
        if (!empty($validated['registration_url']) && !preg_match('/^https?:\/\//i', $validated['registration_url'])) {
            $validated['registration_url'] = 'https://' . $validated['registration_url'];
        }

        $headerPath = null;
        if ($request->hasFile('header')) {
            $headerPath = $request->file('header')->store('event_headers', 'public');
        }

        Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'location' => $validated['location'] ?? null,
            'registration_url' => $validated['registration_url'] ?? null,
            'header_path' => $headerPath,
        ]);

        return back()->with('success', 'Event posted successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::published()->upcoming()->paginate(12);
        return view('events.index', ['events' => $events]);
    }

    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', ['event' => $event]);
    }

    public function create()
    {
        $this->authorize('create', Event::class);
        return view('events.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'location' => 'required|string|max:200',
            'start_datetime' => 'required|date|after_or_equal:now',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $event = Auth::user()->events()->create($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event created successfully');
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $this->authorize('update', $event);
        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'location' => 'required|string|max:200',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $this->authorize('delete', $event);
        
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }
}

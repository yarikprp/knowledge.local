<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event = EventType::all();

        return Inertia::render('rest/EventTypeList', [
            'events' => $event,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        EventType::create($validated);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $event->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $event)
    {
        $event->delete();
    }
}

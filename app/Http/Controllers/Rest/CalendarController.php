<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\EventType;
use App\Models\User;
use App\Models\Test;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $calendars = Calendar::with(['user', 'eventType', 'testing'])->get();

        return Inertia::render('rest/CalendarList', [
            'calendars' => $calendars,
            'events' => EventType::select('id', 'name')->get(),
            'users' => User::select('id', 'name')->get(),
            'tests' => Test::select('id', 'name')->get(),
        ]);
    }

    /**
     * Display a calendar of the resource.
     */
    public function calendars()
    {
        $calendars = Calendar::with(['user', 'eventType', 'testing'])->get();

        return Inertia::render('rest/Calendar', [
            'calendars' => $calendars,
            'events' => EventType::select('id', 'name')->get(),
            'users' => User::select('id', 'name')->get(),
            'tests' => Test::select('id', 'name')->get(),
        ]);
    }

    /**
     * Display a calendar for user of the resource.
     */
    public function calendarsForUser()
    {
        $user = auth()->user();

        $calendars = Calendar::with(['user', 'eventType', 'testing'])
                            ->where('user_id', $user->id)
                            ->get();

        return Inertia::render('TestCalendar', [
            'calendars' => $calendars,
            'events' => EventType::select('id', 'name')->get(),
            'users' => User::where('id', $user->id)->select('id', 'name')->get(),
            'tests' => Test::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_date' => 'required|date',
            'event_type_id' => 'required|exists:event_types,id',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'testing_id' => 'nullable|exists:tests,id',
        ]);

        $validated['is_notified'] = false;

        Calendar::create($validated);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        $validated = $request->validate([
            'event_date' => 'required|date',
            'event_type_id' => 'required|exists:event_types,id',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'testing_id' => 'nullable|exists:tests,id',
            'is_notified' => 'boolean',
        ]);

        $calendar->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
    }

    /**
     * Mark the event as notified.
     */
    public function markAsNotified(Request $request, $id)
    {
        $calendar = Calendar::findOrFail($id);

        if ($calendar->user_id !== auth()->id()) {
            abort(403, 'Доступ запрещен');
        }

        $calendar->is_notified = true;
        $calendar->save();
    }
}

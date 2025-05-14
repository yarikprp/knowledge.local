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
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

        $calendar = Calendar::create($validated);

        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chat_id = env('TELEGRAM_CHAT_ID');

            $eventType = EventType::find($validated['event_type_id'])->name ?? 'Неизвестно';
            $user = User::find($validated['user_id'])->name ?? 'Неизвестный';
            $date = \Carbon\Carbon::parse($validated['event_date'])->locale('ru')->isoFormat('D MMMM YYYY');
            $notes = $validated['notes'] ?? 'Без заметок';

            $message = "🆕 Новое событие:\n"
                    . "📅 Дата: $date\n"
                    . "👤 Пользователь: $user\n"
                    . "📌 Событие: $eventType\n"
                    . "📝 Заметки: $notes";

            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chat_id,
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка отправки Telegram: ' . $e->getMessage());
        }
    }

    public function sendToTelegram($id)
    {
        $calendar = Calendar::with(['user', 'eventType', 'testing'])->findOrFail($id);

        $eventType = $calendar->eventType->name ?? 'Неизвестно';
        $user = $calendar->user->name ?? 'Неизвестный';
        $date = \Carbon\Carbon::parse($calendar->event_date)->locale('ru')->isoFormat('D MMMM YYYY');
        $notes = $calendar->notes ?? 'Без заметок';
        $testName = $calendar->testing->name ?? 'Тест не указан';

        $orderNumber = now()->format('Y') . '-' . random_int(10000, 99999);

        $pdf = Pdf::loadView('pdf.calendar_event', [
                'user' => $user,
                'eventType' => $eventType,
                'date' => $date,
                'notes' => $notes,
                'testName' => $testName,
                'orderNumber' => $orderNumber,
            ])
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true)
            ->setPaper('a4')
            ->setWarnings(false);

        $pdfFilename = "Приказ_{$orderNumber}.pdf";
        $pdfPath = 'calendar_orders/' . $pdfFilename;

        Storage::disk('local')->put($pdfPath, $pdf->output());

        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chat_id = env('TELEGRAM_CHAT_ID');

            $response = Http::attach(
                'document',
                Storage::disk('local')->get($pdfPath),
                $pdfFilename
            )->post("https://api.telegram.org/bot{$token}/sendDocument", [
                'chat_id' => $chat_id,
                'caption' => "📄 Приказ №{$orderNumber}\n👤 {$user}\n🧪 {$eventType}\n🕒 {$date}",
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

        $isUpdated = false;
        if ($calendar->event_date != $validated['event_date'] || $calendar->event_type_id != $validated['event_type_id'] || $calendar->notes != $validated['notes']) {
            $isUpdated = true;
        }

        $calendar->update($validated);

        if ($isUpdated) {
            try {
                $token = env('TELEGRAM_BOT_TOKEN');
                $chat_id = env('TELEGRAM_CHAT_ID');

                $eventType = EventType::find($validated['event_type_id'])->name ?? 'Неизвестно';
                $user = User::find($validated['user_id'])->name ?? 'Неизвестный';
                $date = \Carbon\Carbon::parse($validated['event_date'])->locale('ru')->isoFormat('D MMMM YYYY');
                $notes = $validated['notes'] ?? 'Без заметок';

                $message = "📝 Обновление события:\n"
                        . "📅 Дата: $date\n"
                        . "👤 Пользователь: $user\n"
                        . "📌 Событие: $eventType\n"
                        . "📝 Заметки: $notes";

                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => $message,
                ]);
            } catch (\Exception $e) {
                \Log::error('Ошибка отправки Telegram: ' . $e->getMessage());
            }
        }
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

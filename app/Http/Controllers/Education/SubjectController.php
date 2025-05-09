<?php

namespace App\Http\Controllers\Education;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Material;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subject = Subject::all();

        return Inertia::render('education/SubjectList', [
            'subject' => $subject,
        ]);

    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $subject = Subject::all();

        return Inertia::render('education/Subject', [
            'subject' => $subject,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Subject::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $subject->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): Response
    {
        $subject->load('materials');

        return Inertia::render('education/SubjectShow', [
            'subject' => $subject,
        ]);
    }

    public function downloadPdf(Subject $subject)
    {
        $subject->load('materials');

        $pdf = Pdf::loadView('pdf.subject', ['subject' => $subject]);

        return $pdf->download("{$subject->name}.pdf");
    }
}

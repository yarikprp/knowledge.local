<?php

namespace App\Http\Controllers\Education;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subject;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::with('subject')->get();
        $subjects = Subject::select('id', 'name')->get();

        return Inertia::render('education/MaterialList', [
            'materials' => $materials,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Material::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        $material->load('subject');

        return Inertia::render('education/MaterialShow', [
            'material' => $material,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $material->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();
    }
}

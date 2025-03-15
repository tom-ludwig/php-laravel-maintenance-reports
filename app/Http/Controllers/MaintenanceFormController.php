<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceReport;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MaintenanceFormController extends Controller
{
    public function projectEntry()
    {
        return Inertia::render('ProjectEntry');
    }

    /**
     * Display the maintenance form for a specific project
     */
    public function show($id)
    {
        $project = \App\Models\Project::findOrFail($id);
        
        return Inertia::render('MaintenanceForm', [
            'project' => $project
        ]);
    }

    /**
     * Store a new maintenance report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:done,not_complete,in_progress,repair_needed'],
            'notes' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        $report = MaintenanceReport::create([
            'project_id' => $validated['project_id'],
            'name' => $validated['name'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('maintenance-images', 'public');
                $paths[] = $path;
            }
            $report->update(['images' => $paths]);
        }

        return redirect()->back()->with('success', 'Report submitted successfully');
    }
} 
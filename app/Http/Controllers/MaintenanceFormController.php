<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceReport;
use App\Models\ReportImage;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MaintenanceFormController extends Controller
{
    use AuthorizesRequests;

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
            'status' => ['required', 'string', 'in:done,not_completed,in_progress,repair_needed'],
            'notes' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048']
        ]);

        $report = MaintenanceReport::create([
            'project_id' => $validated['project_id'],
            'name' => $validated['name'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $path = $image->store('maintenance-images', 'public');
                
                ReportImage::create([
                    'maintenance_report_id' => $report->id,
                    'name' => $originalName,
                    'path' => $path
                ]);
            }
        }

        return redirect()->back()->with('success', 'Report submitted successfully');
    }

    /**
     * Delete a maintenance report and its associated images
     */
    public function destroy(MaintenanceReport $report)
    {
        $this->authorize('delete', $report);

        // Delete associated images from storage
        foreach ($report->attachments as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // The report and its images will be deleted due to the cascade relationship
        $report->delete();

        return redirect()->back()->with('success', 'Report deleted successfully');
    }
} 
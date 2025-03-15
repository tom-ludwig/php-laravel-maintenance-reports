<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create(Project $project)
    {
        return Inertia::render('Reports/Create', [
            'project' => $project
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'document_number' => 'required|string|max:255',
            'reporter_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:done,half,needs_repair,not_completed',
            'images.*' => 'image|max:10240'
        ]);

        $report = Report::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('report-images', 'public');
                
                ReportImage::create([
                    'report_id' => $report->id,
                    'image_path' => $path
                ]);
            }
        }

        return Inertia::render('ThankYou');
    }
}

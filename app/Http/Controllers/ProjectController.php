<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Projects/Index', [
            'projects' => Project::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_date' => 'required|date',
        ]);

        $project = Project::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        // Store the form URL
        $project->form_url = url("/reports/create/{$project->id}");
        $project->save();

        return redirect()->route('projects.index');
    }

    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->back()->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete project');
        }
    }

    public function showQrCode(Project $project)
    {
        // Check if the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('projects/QrCode', [
            'project' => $project->only('id', 'name', 'form_url')
        ]);
    }

    public function showReports(Project $project)
    {
        // Check if the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('projects/Reports', [
            'project' => $project->only('id', 'name', 'description', 'project_date'),
            'reports' => $project->reports()
                ->with('attachments')
                ->latest()
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'submitted_at' => $report->created_at,
                        'submitter_name' => $report->submitter_name,
                        'submitter_email' => $report->submitter_email,
                        'notes' => $report->notes,
                        'attachments' => $report->attachments->map(function ($attachment) {
                            return [
                                'id' => $attachment->id,
                                'name' => $attachment->name,
                                'url' => Storage::url($attachment->path)
                            ];
                        })
                    ];
                })
        ]);
    }
}

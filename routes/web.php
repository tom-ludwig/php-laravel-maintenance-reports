<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'name' => config('app.name'),
        'quote' => [
            'message' => 'It is never too late to be what you might have been.',
            'author' => 'George Eliot'
        ]
    ]);
});

Route::get('/scan', function () {
    return Inertia::render('Scan');
})->name('scan');

// Update the QR code route to use authentication and private storage
Route::get('/qr-codes/{filename}', function ($filename) {
    $path = 'qrcodes/' . $filename;
    if (!Storage::exists($path)) {
        abort(404);
    }
    return response(Storage::get($path))
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=31536000');
})->middleware('auth')->name('qr-codes.show');

// Guest-only routes (for scanning QR codes and submitting reports)
Route::middleware('guest')->group(function () {
    Route::get('/reports/create/{project}', [ReportController::class, 'create'])
        ->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])
        ->name('reports.store');
});

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Projects
    Route::get('/projects', function () {
        return Inertia::render('projects/Index', [
            'projects' => \App\Models\Project::where('user_id', auth()->id())
                ->latest()
                ->get()
        ]);
    })->name('projects.index');

    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    // Project Reports
    Route::get('/projects/{project}/reports', [ProjectController::class, 'showReports'])
        ->name('projects.reports');

    // QR Code Page
    Route::get('/projects/{project}/qr-code', [ProjectController::class, 'showQrCode'])
        ->name('projects.qr-code');

    // Reports (viewing submitted reports)
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
});

// Thank you page
Route::get('/thank-you', function () {
    return Inertia::render('ThankYou');
})->name('thank-you');

// Include remaining auth routes
require __DIR__.'/auth.php';
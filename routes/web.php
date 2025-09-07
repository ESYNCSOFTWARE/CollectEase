<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, CaseController, AssignmentController, StatusController, EscalationController
};
use Illuminate\Http\Request;
use App\Http\Controllers\CaseBulkUploadController;
 use Illuminate\Support\Carbon;
 
Route::get('/', function () {
    return view('login');
})->name('login');

 Route::post('/', function (Request $request) {
    $email = $request->input('email');
 
    $name = strstr($email, '@', true) ?: $email;

    session(['user_name' => ucfirst($name)]);

    return redirect()->route('dashboard');
});
Route::get('/logout', function () {
    return view('login');
})->name('logout');
// Dashboard (optional stats)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Assignment Module
Route::prefix('assignments')->name('assignments.')->group(function () {
    // List + filters (your “All Assignments”)
    Route::get('/index', [CaseController::class, 'index'])->name('index');

    // Case creation (also supports optional initial assign)
    Route::get('/create', [CaseController::class, 'create'])->name('create');
    Route::post('/create', [CaseController::class, 'store'])->name('store');

    // Manual assign multiple cases to a collector
    Route::get('/assign', [AssignmentController::class, 'form'])->name('assign.form');
    Route::post('/assign', [AssignmentController::class, 'store'])->name('assign.store');
Route::post('/assignments/bulk-assign', [AssignmentController::class, 'bulkAssign'])->name('bulkAssign');
    // Re-assignment of a specific case
    Route::get('/{case}/reassign', [AssignmentController::class, 'reassignForm'])->name('reassign.form');
    Route::post('/{case}/reassign', [AssignmentController::class, 'reassign'])->name('reassign');

    // Status monitoring + update
    Route::get('/status', [StatusController::class, 'index'])->name('status');
    Route::post('/{case}/status', [StatusController::class, 'update'])->name('status.update');

    // Escalation
    Route::get('/{case}/escalate', [EscalationController::class, 'create'])->name('escalate.form');
    Route::post('/{case}/escalate', [EscalationController::class, 'store'])->name('escalate.store');

});
 
Route::get('/cases/bulk-upload', [CaseBulkUploadController::class, 'showForm'])->name('bulk.upload');
Route::post('/cases/bulk-upload', [CaseBulkUploadController::class, 'handleUpload'])->name('bulk.handle');



Route::get('/cases/download-template', function () {
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="cases_template.csv"',
    ];

    $columns = [
        ['client','case_type','region','debtor_name','debtor_contact','amount_due','due_date','notes'],
    ];

    $callback = function() use ($columns) {
        $file = fopen('php://output', 'w');
        foreach ($columns as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
})->name('bulk.template');



Route::get('/assignments/priority-due-date', [AssignmentController::class, 'priorityDueDate'])
    ->name('assignments.priority_due_date');

    Route::post('/assignments/auto-update-priorities', [AssignmentController::class, 'autoUpdatePriorities'])
    ->name('assignments.autoUpdatePriorities');

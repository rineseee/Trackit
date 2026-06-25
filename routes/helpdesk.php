<?php

use App\Http\Controllers\HelpdeskDashboardController;
use App\Http\Controllers\HelpdeskIssueController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/helpdesk', [HelpdeskDashboardController::class, 'index'])->name('helpdesk.dashboard');

    // Issues
    Route::get('/helpdesk/issues', [HelpdeskIssueController::class, 'index'])->name('helpdesk.issues.index');
    Route::get('/helpdesk/issues/{issue}', [HelpdeskIssueController::class, 'show'])->name('helpdesk.issues.show');
    Route::patch('/helpdesk/issues/{issue}', [HelpdeskIssueController::class, 'update'])->name('helpdesk.issues.update');
    Route::delete('/helpdesk/issues/{issue}', [HelpdeskIssueController::class, 'destroy'])->name('helpdesk.issues.destroy');

    // Comments
    Route::post('/helpdesk/issues/{issue}/comments', [HelpdeskIssueController::class, 'addComment'])->name('helpdesk.comments.store');
    Route::delete('/helpdesk/issues/{issue}/comments/{comment}', [HelpdeskIssueController::class, 'deleteComment'])->name('helpdesk.comments.destroy');

    // Tags
    Route::post('/helpdesk/issues/{issue}/tags', [HelpdeskIssueController::class, 'updateTags'])->name('helpdesk.tags.update');
    Route::delete('/helpdesk/issues/{issue}/tags/{tag}', [HelpdeskIssueController::class, 'removeTag'])->name('helpdesk.tags.remove');
});

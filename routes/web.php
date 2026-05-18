<?php

use App\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Route;

// ─── Public Pages ─────────────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('welcome');

// ─── Authenticated Routes (any logged-in user) ───────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard redirect
    Route::get('/dashboard', fn() => redirect()->route('candidates.index'))->name('dashboard');

    // Candidates CRUD (controller handles role enforcement internally)
    Route::resource('candidates', CandidateController::class);

    // Voting
    Route::post('/vote', [CandidateController::class, 'storeVote'])->name('vote.store');

    // Election lifecycle — all guarded inside the controller
    Route::post('/election/toggle',  [CandidateController::class, 'toggleElection'])->name('election.toggle');
    Route::post('/election/certify', [CandidateController::class, 'certifyResults'])->name('election.certify');
    Route::post('/election/publish', [CandidateController::class, 'publishResults'])->name('election.publish');
    Route::post('/election/archive', [CandidateController::class, 'archiveSystem'])->name('election.archive');
    Route::get('/election/archives', [CandidateController::class, 'archives'])->name('election.archives');

    // Role-specific views
    Route::get('/history', [CandidateController::class, 'history'])->name('history');     // Voter
    Route::get('/ledger',  [CandidateController::class, 'ledger'])->name('ledger');       // Auditor

    // Admin Access Control
    Route::get('/access-control',          [CandidateController::class, 'accessControl'])->name('access');
    Route::post('/access-control/auditor', [CandidateController::class, 'storeAuditor'])->name('auditor.store');

    // Profile — available to every role
    Route::get('/profile',    [CandidateController::class, 'profile'])->name('profile.show');
    Route::put('/profile',    [CandidateController::class, 'updateProfile'])->name('profile.update');
});

require __DIR__ . '/auth.php';
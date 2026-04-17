<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });
Route::get('/home', function () { return view('home'); })->name('home');
Route::get('/about', function () { return view('about'); })->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return redirect()->route('candidates.index'); })->name('dashboard');
    Route::resource('candidates', CandidateController::class);
    Route::post('/vote', [CandidateController::class, 'storeVote'])->name('vote.store');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/election/toggle', [\App\Http\Controllers\CandidateController::class, 'toggleElection'])->name('election.toggle');
    Route::get('/history', [\App\Http\Controllers\CandidateController::class, 'history'])->name('history');
    Route::get('/ledger', [\App\Http\Controllers\CandidateController::class, 'ledger'])->name('ledger');
});

require __DIR__.'/auth.php';

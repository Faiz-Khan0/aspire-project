<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserGroup;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $services = Service::with('subservices')->get();
    return view('welcome', compact('services'));
});

Route::middleware(['auth', 'user_group:a,s'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// store checkin 
Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.store');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware([
    'auth', // check if user logged in 
    'verified',
    'user_group:a,s', // Only allow Admin (a) or Staff (s)
])->name('dashboard');

require __DIR__ . '/auth.php';

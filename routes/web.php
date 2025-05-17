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

// store checkin 
Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.store');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([
    'auth',
    new EnsureUserGroup('a', 's'), // allow admin and staff
])->group(function () {
    Route::get('/dashboard', fn () => 'Dashboard for admins and staff');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
//comment

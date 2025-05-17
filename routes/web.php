<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubServiceController;
use App\Http\Controllers\UserController;

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



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware([
    'auth', // check if user logged in 
    'verified',
    'user_group:a,s', // Only allow Admin (a) or Staff (s)
])->name('dashboard');

Route::get('/createUser', function () {
    return view('usermanagement');
})->middleware([
    'auth', // check if user logged in 
    'verified',
    'user_group:a,s', // Only allow Admin (a) or Staff (s)
])->name('userManagement');


Route::middleware(['auth'])->group(function () {
    Route::get('/subservices/create', [SubServiceController::class, 'create'])->middleware([
        'auth', // check if user logged in 
        'verified',
        'user_group:a,s', // Only allow Admin (a) or Staff (s)
    ])->name('subservices.create');
    Route::post('/subservices', [SubserviceController::class, 'store'])->middleware([
        'auth', // check if user logged in 
        'verified',
        'user_group:a,s', // Only allow Admin (a) or Staff (s)
    ])->name('subservices.store');
});

Route::get('/users/create', [UserController::class, 'create'])->middleware([
    'auth', // check if user logged in 
    'verified',
    'user_group:a,s', // Only allow Admin (a) or Staff (s)
])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->middleware([
    'auth', // check if user logged in 
    'verified',
    'user_group:a,s', // Only allow Admin (a) or Staff (s)
])->name('users.store');

require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoboothController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('/ar/scan', [MarkerController::class, 'showAR']);
Route::prefix('markers')->group(function () {
    // ... routes existing lainnya ...

    Route::get('/upload-mind', [MarkerController::class, 'showMindUploadForm'])->name('markers.upload-mind-form');
    Route::post('/upload-mind', [MarkerController::class, 'uploadMindFile'])->name('markers.upload-mind');
});
Route::resource('markers', MarkerController::class);
Route::resource('photobooths', PhotoboothController::class);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PhotoboothController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('markers')->group(function () {
        // ... routes existing lainnya ...

        Route::get('/upload-mind', [MarkerController::class, 'showMindUploadForm'])->name('markers.upload-mind-form');
        Route::post('/upload-mind', [MarkerController::class, 'uploadMindFile'])->name('markers.upload-mind');
    });
    Route::get('/markers/upload-patt', [MarkerController::class, 'showPattUploadForm'])->name('patt.upload.form');
    Route::post('/markers/upload-patt', [MarkerController::class, 'uploadPattFile'])->name('patt.upload');
    Route::post('/markers/delete-patt', [MarkerController::class, 'deletePattFile'])->name('patt.delete');
    Route::get('/markers/monthly-photos', [MarkerController::class, 'monthlyPhotos'])->name('markers.monthly-photos');
    Route::get('/markers/download-monthly-photos', [MarkerController::class, 'downloadMonthlyPhotos'])->name('markers.download-monthly-photos');
    Route::resource('markers', MarkerController::class);
    Route::resource('photobooths', PhotoboothController::class);
    Route::get('/mind-ar/compile', function () {
        return response()->file(public_path('mind-ar-js/compile.html'));
    });
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
Route::get('/ar', [MarkerController::class, 'showARku']);
Route::get('/', [MarkerController::class, 'showAR'])->name('ar.view');

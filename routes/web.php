<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

  Route::get('/', [ProfileController::class, 'index'])->name('profile.index');





Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/image', [ProfileController::class, 'image'])->name('image.store');
    // Route::post('/image/delete', [ProfileController::class, 'deleteImage'])->name('image.delete');
    // Route::post('/image/edit', [ProfileController::class, 'editImage'])->name('image.edit');

});

require __DIR__.'/auth.php';

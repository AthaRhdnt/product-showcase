<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;

Route::get('/', [PublicController::class, 'index'])->name('public.index');

Route::get('/compare', [PublicController::class, 'compare'])->name('public.compare');
Route::post('/compare/toggle/{id}', [PublicController::class, 'toggleCompare'])->name('public.compare.toggle');
Route::get('/compare/clear', [PublicController::class, 'compareClear'])->name('public.clear');
Route::get('/product/{product}', [PublicController::class, 'show'])->name('public.show');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth:admin', 'verified'])->name('dashboard');

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('attributes', \App\Http\Controllers\AttributeController::class);
    Route::resource('settings', \App\Http\Controllers\SettingController::class);
});

require __DIR__.'/auth.php';

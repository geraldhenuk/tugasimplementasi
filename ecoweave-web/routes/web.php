<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\EducationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Beranda AI & Tracking Interaksi
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/interactions', [HomeController::class, 'recordInteraction'])->name('interactions.store');

    // Katalog Belanja (Shop)
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

    // Portal Edukasi
    Route::get('/education', [EducationController::class, 'index'])->name('education.index');
    Route::get('/education/{article}', [EducationController::class, 'show'])->name('education.show');

    // Rute Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/cart/add/{product}', function () {
    return back()->with('success', 'Fitur keranjang sedang disiapkan!');
})->name('cart.add');
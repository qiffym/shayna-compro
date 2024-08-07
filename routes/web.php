<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// Route::get('/', Controllers\HomeController::class)->name('homepage');
Route::get('/', [Controllers\FrontController::class, 'index'])->name('front.index');
Route::get('/team', [Controllers\FrontController::class, 'team'])->name('front.team');
Route::get('/about', [Controllers\FrontController::class, 'about'])->name('front.about');
Route::get('/appointment', [Controllers\FrontController::class, 'appointment'])->name('front.appointment');
Route::post('/appointment-store', [Controllers\FrontController::class, 'appointment_store'])->name('front.appointment_store');

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('profile', [Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware(['can:manage statistics'])->group(function () {
            Route::resource('statistics', Controllers\CompanyStatisticController::class);
        });
        Route::middleware(['can:manage products'])->group(function () {
            Route::resource('products', Controllers\ProductController::class);
        });
        Route::middleware(['can: manage principles'])->group(function () {
            Route::resource('principles', Controllers\OurPrincipleController::class);
        });
        Route::middleware(['can:manage testimonials'])->group(function () {
            Route::resource('testimonials', Controllers\TestimonialController::class);
        });
        Route::middleware(['can:manage clients'])->group(function () {
            Route::resource('clients', Controllers\ProjectClientController::class);
        });
        Route::middleware(['can:manage teams'])->group(function () {
            Route::resource('teams', Controllers\OurTeamController::class);
        });
        Route::middleware(['can:manage abouts'])->group(function () {
            Route::resource('abouts', Controllers\CompanyAboutController::class);
        });
        Route::middleware(['can:manage appointments'])->group(function () {
            Route::resource('appointments', Controllers\AppointmentController::class);
        });
        Route::middleware(['can:manage hero sections'])->group(function () {
            Route::resource('hero_sections', Controllers\HeroSectionController::class);
        });
    });
});

require __DIR__ . '/auth.php';

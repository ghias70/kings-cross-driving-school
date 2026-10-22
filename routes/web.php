<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/lessons', [PageController::class, 'lessons'])->name('lessons');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/areas', [PageController::class, 'areas'])->name('areas');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:8,1')->name('contact.store');

Route::redirect('/prices', '/lessons', 301);
Route::redirect('/reviews', '/gallery', 301);

Route::get('/book', [BookingController::class, 'create'])->name('book');
Route::get('/book/slots', [BookingController::class, 'slots'])->name('book.slots');
Route::post('/book', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('book.store');
Route::get('/booking/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/cancel/{token}', [BookingController::class, 'cancel'])->name('booking.cancel');

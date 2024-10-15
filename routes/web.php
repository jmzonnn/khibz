<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;





// Home page route
Route::get('/', function () {
    return view('home');
})->name('home');


// Order routes
// Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
// Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('/submit-order', [OrderController::class, 'store']);

// Reservation routes
Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
Route::get('/reservation/details', [ReservationController::class, 'details'])->name('reservation.details');
Route::get('/reservation/form', [ReservationController::class, 'form'])->name('reservation.form');
Route::post('/reservation/submit', [ReservationController::class, 'submit'])->name('reservation.submit');
Route::get('/check-table/{tableId}', [MenuController::class, 'checkTableStatus'])->name('check.table'); // New route
Route::get('/menu/{tableId}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add'); // New route for adding items to the cart

Route::post('/order', [OrderController::class, 'store']);
Route::post('/store-table-id', [CartController::class, 'storeTableId']);

// Routes to update and remove items from the cart
Route::post('/cart/update/{index}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{index}', [CartController::class, 'remove'])->name('cart.remove');
// Menu page route
Route::get('/menu', function () {
    return view('menu');
})->name('menu');

// About Us page route
Route::get('/about', function () {
    return view('about');
})->name('about');

// Admin routes with middleware protection
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/reservations', [AdminController::class, 'manageReservation'])->name('admin.reservations');
    Route::get('/admin/menu', [MenuItemController::class, 'index'])->name('admin.menu'); // Ensure this is correct
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::post('/admin/reservations/update-status', [ReservationController::class, 'updateStatus'])->name('reservation.update.status');
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::delete('/admin/reservations/{id}', [ReservationController::class, 'delete'])->name('reservation.delete');
    Route::post('/admin/reservations/mark-done', [ReservationController::class, 'markReservationsAsDone'])->name('reservation.markDone');
    Route::get('/admin/reservations/history', [ReservationController::class, 'history'])->name('admin.reservations.history');
    Route::resource('menu-items', MenuItemController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/admin/orders/{order}', [OrderController::class, 'show']);
    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::post('/admin/orders/{order}/update-status', [OrderController::class, 'updateStatus']);
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('events', [EventController::class, 'index'])->name('admin.events'); // List events
    Route::get('events/create', [EventController::class, 'create'])->name('admin.events.create'); // Form to create a new event
    Route::post('events', [EventController::class, 'store'])->name('admin.events.store'); // Store the new event
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy'); // Delete event
    Route::get('/admin/events/{id}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
    Route::post('/admin/events/{id}', [EventController::class, 'update'])->name('admin.events.update');
});

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
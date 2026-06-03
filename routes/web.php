<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CateringController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CateringPackageController;
use App\Http\Controllers\OrderTrackingController;

Route::get('/', [HomeController::class, 'index']);

Route::resource('orders', OrderController::class);

Route::get(
    '/catering',
    [CateringController::class, 'index']
)->name('catering.index');

Route::get(
    '/catering/{package}',
    [CateringController::class, 'show']
)->name('catering.show');

Route::post(
    '/feedback',
    [FeedbackController::class, 'store']
)->name('feedback.store');

Route::get(
    '/cek-pesanan',
    [OrderTrackingController::class, 'index']
)->name('orders.track');

Route::post(
    '/cek-pesanan',
    [OrderTrackingController::class, 'search']
)->name('orders.track.search');

Route::post('/helpdesk',
    [HelpdeskController::class, 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard',
            [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('menus', MenuController::class);

        Route::resource(
            'catering-packages',
            CateringPackageController::class
        );

        Route::resource(
            'orders',
            AdminOrderController::class
        );
    });



Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/orders',
            [AdminOrderController::class, 'index']
        )->name('orders.index');

        Route::get(
            '/orders/{order}',
            [AdminOrderController::class, 'show']
        )->name('orders.show');

        Route::patch(
            '/orders/{order}/status',
            [AdminOrderController::class, 'updateStatus']
        )->name('orders.status');
    });

require __DIR__.'/auth.php';

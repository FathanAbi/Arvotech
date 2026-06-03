<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\FeedbackController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\CateringController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CateringPackageController;
use App\Http\Controllers\OrderTrackingController;

use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\Admin\HelpdeskController as AdminHelpdeskController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

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

Route::get(
    '/helpdesk',
    [HelpdeskController::class, 'create']
);

Route::post(
    '/helpdesk',
    [HelpdeskController::class, 'store']
);

Route::get(
    '/helpdesk/search',
    [HelpdeskController::class, 'searchForm']
)->name('helpdesk.search.form');

Route::post(
    '/helpdesk/search',
    [HelpdeskController::class, 'search']
)->name('helpdesk.search');

Route::get(
    '/helpdesk/{ticket}',
    [HelpdeskController::class, 'show']
)->name('helpdesk.show');

Route::post(
    '/helpdesk/{ticket}/reply',
    [HelpdeskController::class, 'reply']
);

Route::get(
    '/helpdesk/{ticket}',
    [HelpdeskController::class, 'show']
)->name('helpdesk.show');

Route::post(
    '/helpdesk/{ticket}/reply',
    [HelpdeskController::class, 'reply']
);

Route::get(
    '/feedback',
    [FeedbackController::class, 'create']
)->name('feedback.create');

Route::post(
    '/feedback',
    [FeedbackController::class, 'store']
)->name('feedback.store');

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

    });

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

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/helpdesk',
            [AdminHelpdeskController::class, 'index']
        )->name('helpdesk.index');

        Route::get(
            '/helpdesk/{id}',
            [AdminHelpdeskController::class, 'show']
        )->name('helpdesk.show');

        Route::post(
            '/helpdesk/{id}/reply',
            [AdminHelpdeskController::class, 'reply']
        )->name('helpdesk.reply');

    });

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/feedback',
            [AdminFeedbackController::class, 'index']
        )->name('feedback.index');

        Route::get(
            '/feedback/{id}',
            [AdminFeedbackController::class, 'show']
        )->name('feedback.show');

    });

    

require __DIR__.'/auth.php';

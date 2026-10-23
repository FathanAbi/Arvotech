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

// SEC-002 (IDOR-01): only the public booking form is reachable by guests.
// index/edit/update/destroy are gone (they were unhandled 500s), and the
// remaining read/upload routes below require a valid signed URL.
Route::get(
    '/orders/create',
    [OrderController::class, 'create']
)->name('orders.create');

Route::post(
    '/orders',
    [OrderController::class, 'store']
)->middleware('throttle:10,1')->name('orders.store');

Route::get(
    '/orders/{order}',
    [OrderController::class, 'show']
)->middleware('signed')->name('orders.show');

Route::get(
    '/catering',
    [CateringController::class, 'index']
)->name('catering.index');

Route::get(
    '/catering/{package}',
    [CateringController::class, 'show']
)->name('catering.show');

Route::get(
    '/cek-pesanan',
    [OrderTrackingController::class, 'index']
)->name('orders.track');

Route::post(
    '/orders/{order}/upload-proof',
    [OrderController::class, 'uploadProof']
)->middleware(['signed', 'throttle:10,1'])->name('orders.upload-proof');

// SEC-007 (STOR-01): bukti pembayaran disimpan di disk privat dan hanya
// bisa dibuka lewat tautan bertanda tangan.
Route::get(
    '/orders/{order}/payment-proof',
    [OrderController::class, 'paymentProof']
)->middleware('signed')->name('orders.payment-proof');

Route::post(
    '/cek-pesanan',
    [OrderTrackingController::class, 'search']
)->middleware('throttle:30,1')->name('orders.track.search');

Route::get(
    '/helpdesk',
    [HelpdeskController::class, 'create']
);

Route::post(
    '/helpdesk',
    [HelpdeskController::class, 'store']
)->middleware('throttle:10,1');

Route::get(
    '/helpdesk/search',
    [HelpdeskController::class, 'searchForm']
)->name('helpdesk.search.form');

Route::post(
    '/helpdesk/search',
    [HelpdeskController::class, 'search']
)->middleware('throttle:30,1')->name('helpdesk.search');

Route::get(
    '/helpdesk/{ticket}',
    [HelpdeskController::class, 'show']
)->name('helpdesk.show');

Route::post(
    '/helpdesk/{ticket}/reply',
    [HelpdeskController::class, 'reply']
)->middleware('throttle:10,1');

Route::get(
    '/feedback',
    [FeedbackController::class, 'create']
)->name('feedback.create');

Route::post(
    '/feedback',
    [FeedbackController::class, 'store']
)->middleware('throttle:10,1')->name('feedback.store');

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

        Route::patch(
            '/orders/{order}/status',
            [AdminOrderController::class, 'updateStatus']
        )->name('orders.status');

        // SEC-007 (STOR-01): admin-only stream of the private receipt.
        Route::get(
            '/orders/{order}/payment-proof',
            [AdminOrderController::class, 'paymentProof']
        )->name('orders.payment-proof');

        // SEC-001 (BAC-01): admin-only. Previously guarded by 'auth' only,
        // letting any registered customer impersonate the administrator.
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

        Route::post(
            '/helpdesk/{id}/close',
            [AdminHelpdeskController::class, 'close']
        )->name('helpdesk.close');

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

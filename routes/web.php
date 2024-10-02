<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


Route::middleware(['auth'])->group(function () {
Route::get('/index', [DashboardController::class, 'index'])->name('index');
Route::post('/razorpay/payment/callback', [PaymentController::class, 'handlePaymentCallback'])->name('razorpay.payment.callback');
Route::post('/create-order', [PaymentController::class, 'createOrder']);
Route::post('/payment-callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment-failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');
Route::post('/payment-callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
Route::get('/download-invoice/{id}', [PaymentController::class, 'downloadInvoice'])->name('download.invoice');
Route::get('/subscriptions', [SubscriptionController::class, 'showSubscriptionForm'])->name('subscriptions.index');
Route::post('/subscriptions', [SubscriptionController::class, 'updateSubscription'])->name('subscriptions.update');
});

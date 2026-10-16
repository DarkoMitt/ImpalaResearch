
<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminResearchRequestController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AdminContactRequestController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResearchRequestController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionCancellationController;
use App\Http\Controllers\UserResearchRequestController;
use App\Http\Middleware\SubmitPendingResearchRequest;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::view('/', 'pages.home')->name('home');

/*
|--------------------------------------------------------------------------
| Contact & Demo
|--------------------------------------------------------------------------
|
| GET: Display the public Contact & Demo page.
| POST: Validate and store contact/demo submissions.
|
*/

Route::view('/contact', 'pages.contact')
    ->name('contact');

Route::post(
    '/contact',
    [ContactController::class, 'store']
)->name('contact.store');

Route::view('/thank-you', 'pages.thank-you')
    ->name('thank-you');

Route::get('/pricing', [PricingController::class, 'index'])
    ->name('pricing');

Route::get('/payment', [PaymentController::class, 'index'])
    ->name('payment');

/*
|--------------------------------------------------------------------------
| Research Request Submission
|--------------------------------------------------------------------------
*/

Route::post(
    '/research-requests',
    [ResearchRequestController::class, 'store']
)->name('research-requests.store');

/*
|--------------------------------------------------------------------------
| Stripe Webhook
|--------------------------------------------------------------------------
*/

Route::post(
    '/stripe/webhook',
    [StripeWebhookController::class, 'handle']
)
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Authenticated User Pages
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('pages.user-dashboard');
    })
        ->middleware(SubmitPendingResearchRequest::class)
        ->name('dashboard');

    Route::view('/profile-settings', 'pages.profile-settings')
        ->name('profile-settings');

    Route::get(
        '/my-studies',
        [UserResearchRequestController::class, 'index']
    )->name('my-studies.index');

    Route::get(
        '/my-studies/{researchRequest}',
        [UserResearchRequestController::class, 'show']
    )->name('my-studies.show');

    /*
    |--------------------------------------------------------------------------
    | Stripe Checkout
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/checkout/stripe',
        [StripeCheckoutController::class, 'store']
    )->name('checkout.stripe');

    /*
    |--------------------------------------------------------------------------
    | Subscription Cancellation / Refund
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/subscription/cancellation-request',
        [SubscriptionCancellationController::class, 'store']
    )->name('subscriptions.cancellation-request');

    Route::post(
        '/subscription/resume',
        [SubscriptionCancellationController::class, 'resume']
    )->name('subscriptions.resume');
});

/*
|--------------------------------------------------------------------------
| Profile Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [AdminDashboardController::class, 'index']
        )
            ->middleware(SubmitPendingResearchRequest::class)
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Admin Users
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users',
            [AdminUserController::class, 'index']
        )->name('users');

        /*
        |--------------------------------------------------------------------------
        | Admin User Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users/{user}',
            [AdminUserController::class, 'show']
        )->name('users.show');

        /*
        |--------------------------------------------------------------------------
        | Admin Research Requests
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/research-requests',
            [AdminResearchRequestController::class, 'index']
        )->name('research-requests.index');

        Route::get(
            '/research-requests/{researchRequest}',
            [AdminResearchRequestController::class, 'show']
        )->name('research-requests.show');

        Route::patch(
            '/research-requests/{researchRequest}/status',
            [AdminResearchRequestController::class, 'updateStatus']
        )->name('research-requests.update-status');

        /*
        |--------------------------------------------------------------------------
        | Admin Contact & Demo Requests
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/contact-requests',
            [AdminContactRequestController::class, 'index']
        )->name('contact-requests.index');

        Route::get(
            '/contact-requests/{contactRequest}',
            [AdminContactRequestController::class, 'show']
        )->name('contact-requests.show');

        Route::patch(
            '/contact-requests/{contactRequest}',
            [AdminContactRequestController::class, 'update']
        )->name('contact-requests.update');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

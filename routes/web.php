
<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminResearchRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResearchRequestController;
use App\Http\Controllers\UserResearchRequestController;
use App\Http\Middleware\SubmitPendingResearchRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::view('/', 'pages.home')->name('home');

Route::view('/contact', 'pages.contact')
    ->name('contact');

Route::view('/thank-you', 'pages.thank-you')
    ->name('thank-you');

Route::view('/pricing', 'pages.pricing')
    ->name('pricing');

Route::view('/payment', 'pages.payment')
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

        Route::get(
            '/',
            [AdminDashboardController::class, 'index']
        )
            ->middleware(SubmitPendingResearchRequest::class)
            ->name('dashboard');

        Route::get(
            '/users',
            [AdminUserController::class, 'index']
        )->name('users');

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
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

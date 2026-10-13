
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Commands
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Subscription Scheduler
|--------------------------------------------------------------------------
|
| Automatically check for monthly respondent allowances.
| The SubscriptionService prevents duplicate monthly grants.
|
*/

Schedule::command('subscriptions:grant-respondents')
    ->hourly()
    ->withoutOverlapping();

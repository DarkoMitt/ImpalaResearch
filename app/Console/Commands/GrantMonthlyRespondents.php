<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;
use Throwable;

class GrantMonthlyRespondents extends Command
{
    protected $signature = 'subscriptions:grant-respondents';

    protected $description =
        'Grant due monthly respondent allowances for active subscriptions.';

    public function handle(SubscriptionService $service): int
    {
        $processed = 0;
        $monthsGranted = 0;
        $failed = 0;

        Subscription::query()
            ->where('status', 'active')
            ->whereNotNull('next_allowance_at')
            ->where('next_allowance_at', '<=', now())
            ->where('ends_at', '>', now())
            ->orderBy('id')
            ->chunkById(100, function ($subscriptions) use (
                $service,
                &$processed,
                &$monthsGranted,
                &$failed
            ) {
                foreach ($subscriptions as $subscription) {
                    try {
                        $granted = $service
                            ->grantDueMonthlyAllowances($subscription);

                        $processed++;
                        $monthsGranted += $granted;

                    } catch (Throwable $exception) {
                        $failed++;
                        report($exception);

                        $this->error(
                            'Failed subscription ID: ' .
                            $subscription->id
                        );
                    }
                }
            });

        $this->info("Subscriptions checked: {$processed}");
        $this->info("Monthly allowances granted: {$monthsGranted}");
        $this->info("Failures: {$failed}");

        return $failed > 0
            ? self::FAILURE
            : self::SUCCESS;
    }
}

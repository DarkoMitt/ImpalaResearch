<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionStatusChanged;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description =
        'Expire demo subscriptions after their billing period ends.';

    public function handle(): int
    {
        $expired = 0;
        $failed = 0;

        Subscription::query()
            ->where('status', 'active')
            ->where('is_demo', true)
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->orderBy('id')
            ->chunkById(100, function ($subscriptions) use (
                &$expired,
                &$failed
            ) {
                foreach ($subscriptions as $subscription) {
                    try {
                        $changed = DB::transaction(function () use (
                            $subscription
                        ) {
                            $locked = Subscription::query()
                                ->whereKey($subscription->id)
                                ->lockForUpdate()
                                ->firstOrFail();

                            if (
                                $locked->status !== 'active' ||
                                !$locked->is_demo ||
                                !$locked->ends_at ||
                                $locked->ends_at->isFuture()
                            ) {
                                return false;
                            }

                            $locked->update([
                                'status' => 'expired',
                                'next_allowance_at' => null,
                            ]);

                            $locked->user->notify(
                                new SubscriptionStatusChanged(
                                    $locked,
                                    'expired'
                                )
                            );

                            return true;
                        }, 3);

                        if ($changed) {
                            $expired++;
                        }
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

        $this->info("Subscriptions expired: {$expired}");
        $this->info("Failures: {$failed}");

        return $failed > 0
            ? self::FAILURE
            : self::SUCCESS;
    }
}

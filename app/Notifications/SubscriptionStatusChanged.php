<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class SubscriptionStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Subscription $subscription,
        public string $event
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $endDate = $this->subscription->ends_at
            ? $this->subscription->ends_at->format('M d, Y')
            : 'Not available';

        $details = match ($this->event) {
            'activated' => [
                'title' => 'Subscription Activated',
                'message' => "Your subscription has been activated successfully. Your current billing period ends on {$endDate}.",
            ],

            'renewed' => [
                'title' => 'Subscription Renewed',
                'message' => "Your subscription has been renewed successfully. Your new billing period ends on {$endDate}.",
            ],

            'cancellation_scheduled' => [
                'title' => 'Subscription Cancellation Scheduled',
                'message' => "Automatic renewal has been disabled. Your subscription remains active until {$endDate}.",
            ],

            'resumed' => [
                'title' => 'Subscription Resumed',
                'message' => "Automatic renewal has been restored. Your subscription remains active until {$endDate}.",
            ],

            'payment_failed' => [
                'title' => 'Subscription Payment Failed',
                'message' => 'We could not process your subscription payment. Please review your payment method to avoid an interruption in service.',
            ],

            'expired' => [
                'title' => 'Subscription Expired',
                'message' => "Your subscription expired on {$endDate}. Please renew your subscription to continue creating research studies.",
            ],

            default => throw new InvalidArgumentException(
                'Unsupported subscription notification event.'
            ),
        };

        return [
            'title' => $details['title'],
            'message' => $details['message'],
            'url' => route('dashboard'),
            'type' => 'subscription_status',
            'event' => $this->event,
            'subscription_id' => $this->subscription->id,
            'ends_at' => $this->subscription->ends_at?->toIso8601String(),
        ];
    }
}

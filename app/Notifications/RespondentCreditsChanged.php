<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class RespondentCreditsChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Subscription $subscription,
        public string $event,
        public int $amount,
        public int $remaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $details = match ($this->event) {
            'added' => [
                'title' => 'Respondent Credits Added',
                'message' => "{$this->amount} respondent credits have been added to your subscription. You now have {$this->remaining} credits available.",
            ],

            'low' => [
                'title' => 'Low Respondent Credits',
                'message' => "Your respondent credit balance is running low. You have {$this->remaining} credits remaining.",
            ],

            'exhausted' => [
                'title' => 'Respondent Credits Exhausted',
                'message' => 'You have used all available respondent credits for your current subscription.',
            ],

            'refunded' => [
                'title' => 'Respondent Credits Returned',
                'message' => "{$this->amount} respondent credits have been returned to your subscription. Your available balance is now {$this->remaining}.",
            ],

            default => throw new InvalidArgumentException(
                'Unsupported respondent credit notification event.'
            ),
        };

        return [
            'title' => $details['title'],
            'message' => $details['message'],
            'url' => route('dashboard'),
            'type' => 'respondent_credits',
            'event' => $this->event,
            'subscription_id' => $this->subscription->id,
            'amount' => $this->amount,
            'remaining' => $this->remaining,
        ];
    }
}

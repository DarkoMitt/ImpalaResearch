<?php

namespace App\Notifications;

use App\Models\ResearchRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResearchStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public ResearchRequest $study,
        public string $previousStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $studyNumber = $this->study->study_number;

        $details = match ($this->newStatus) {
            'in_progress' => [
                'title' => 'Study Approved',
                'message' => "Your Study {$studyNumber} has been approved and is now in progress.",
            ],

            'review' => [
                'title' => 'Study Under Review',
                'message' => "Your Study {$studyNumber} has entered the review stage.",
            ],

            'completed' => [
                'title' => 'Study Completed',
                'message' => "Your Study {$studyNumber} has been completed.",
            ],

            'rejected' => [
                'title' => 'Study Rejected',
                'message' => $this->rejectionMessage($studyNumber),
            ],

            default => [
                'title' => 'Study Status Updated',
                'message' => "Your Study {$studyNumber} status has been updated.",
            ],
        };

        return [
            'title' => $details['title'],
            'message' => $details['message'],
            'url' => route('my-studies.show', $this->study),
            'type' => 'research_status',
            'study_id' => $this->study->id,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
        ];
    }

    private function rejectionMessage(string|int $studyNumber): string
    {
        $message = "Your Study {$studyNumber} has been rejected.";

        $reason = trim(
            (string) ($this->study->rejection_reason ?? '')
        );

        if ($reason !== '') {
            $message .= " Reason: {$reason}";
        }

        return $message;
    }
}

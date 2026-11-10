<?php

namespace App\Notifications;

use App\Models\ResearchRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewResearchRequestSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public ResearchRequest $study
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $studyNumber = $this->study->study_number;

        return [
            'title' => 'New Research Request',

            'message' => "A new research request (Study {$studyNumber}) has been submitted and is waiting for review.",

            'url' => route(
                'admin.research-requests.show',
                $this->study
            ),

            'type' => 'research_submitted',

            'study_id' => $this->study->id,

            'status' => 'submitted',
        ];
    }
}

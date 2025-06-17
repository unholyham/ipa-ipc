<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Proposal;

class NewProposalSubmittedNotification extends Notification
{
    use Queueable;

    protected $proposal;
    /**
     * Create a new notification instance.
     */
    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Proposal Submitted',
            'message' => 'A new proposal titled "<strong>' . $this->proposal->getProject->projectTitle . '</strong>" has been submitted by <strong>' . $this->proposal->owner->employeeName . '</strong>.',
            'proposal_id' => $this->proposal->id,
            'link' => route('proposal.view', $this->proposal->id),
        ];
    }
}

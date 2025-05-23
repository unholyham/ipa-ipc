<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Proposal;

class ProposalUnderReviewNotification extends Notification
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
            'title' => 'Proposal Under Review',
            'message' => 'Your proposal "' . $this->proposal->projectTitle . '" is now under review.',
            'proposal_id' => $this->proposal->id,
            'link' => route('proposal.view', $this->proposal->id),
        ];
    }
}

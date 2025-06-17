<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Proposal;

class ProposalApprovedNotification extends Notification
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
            'title' => 'Proposal Approved!',
            'message' => 'Your proposal <strong>"' . $this->proposal->getProject->projectTitle . '"</strong> has been approved!',
            'proposal_id' => $this->proposal->id,
            'link' => route('proposal.view', $this->proposal->id),
        ];
    }
}

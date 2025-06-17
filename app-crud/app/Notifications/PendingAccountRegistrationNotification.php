<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Account;

class PendingAccountRegistrationNotification extends Notification
{
    use Queueable;

    protected $account;
    /**
     * Create a new notification instance.
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
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
            'title' => 'New Account Registration Pending',
            'message' => 'A new account registration has been submitted by "<strong>' . $this->account->employeeName . '</strong>".',
            'account_id' => $this->account->accountID,
            'link' => route('admin.users.view', $this->account->accountID), // Link to view the new account details
        ];
    }
}

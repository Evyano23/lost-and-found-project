<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClaimApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly Claim $claim) {}

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
            'type' => 'claim_approved',
            'claim_id' => $this->claim->id,
            'item_id' => $this->claim->item_id,
            'item_title' => $this->claim->item->title,
            'message' => "Your claim on \"{$this->claim->item->title}\" was approved!",
            'url' => route('items.show', $this->claim->item),
        ];
    }
}
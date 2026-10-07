<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClaimSubmittedNotification extends Notification
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
            'type' => 'claim_submitted',
            'claim_id' => $this->claim->id,
            'item_id' => $this->claim->item_id,
            'item_title' => $this->claim->item->title,
            'claimant_name' => $this->claim->user->name,
            'message' => "{$this->claim->user->name} submitted a claim on \"{$this->claim->item->title}\"",
            'url' => route('claims.item-claims', $this->claim->item),
        ];
    }
}

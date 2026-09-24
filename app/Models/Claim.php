<?php

namespace App\Models;

use App\Enums\ClaimStatus;
use App\Enums\ItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'proof_description',
        'proof_image',
        'status',
        'contact_phone',
        'contact_email',
        'response_notes',
        'resolved_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ClaimStatus::class,
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Item being claimed.
     *
     * @return BelongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * User who submitted the claim (claimant).
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Approve this claim and synchronize item status to claimed.
     */
    public function approve(?string $notes = null): void
    {
        DB::transaction(function () use ($notes) {
            $this->update([
                'status' => ClaimStatus::APPROVED,
                'response_notes' => $notes,
                'resolved_at' => now(),
            ]);

            $this->item->update([
                'status' => ItemStatus::CLAIMED,
            ]);

            // Auto-reject any other competing pending claims on this item
            $this->item->claims()
                ->where('id', '!=', $this->id)
                ->where('status', ClaimStatus::PENDING)
                ->update([
                    'status' => ClaimStatus::REJECTED,
                    'response_notes' => 'Another claim was verified and approved.',
                    'resolved_at' => now(),
                ]);
        });
    }

    /**
     * Reject this claim and reset item status if no other pending claims remain.
     */
    public function reject(?string $notes = null): void
    {
        DB::transaction(function () use ($notes) {
            $this->update([
                'status' => ClaimStatus::REJECTED,
                'response_notes' => $notes,
                'resolved_at' => now(),
            ]);

            $hasOtherPending = $this->item->claims()
                ->where('id', '!=', $this->id)
                ->where('status', ClaimStatus::PENDING)
                ->exists();

            if (! $hasOtherPending && $this->item->status === ItemStatus::PENDING_CLAIM) {
                $this->item->update(['status' => ItemStatus::ACTIVE]);
            }
        });
    }

    /**
     * Cancel this claim (by claimant) and reset item status if no other pending claims remain.
     */
    public function cancel(): void
    {
        DB::transaction(function () {
            $this->update([
                'status' => ClaimStatus::CANCELLED,
                'resolved_at' => now(),
            ]);

            $hasOtherPending = $this->item->claims()
                ->where('id', '!=', $this->id)
                ->where('status', ClaimStatus::PENDING)
                ->exists();

            if (! $hasOtherPending && $this->item->status === ItemStatus::PENDING_CLAIM) {
                $this->item->update(['status' => ItemStatus::ACTIVE]);
            }
        });
    }

    /**
     * Reports made against this claim.
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VerificationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'question',
    ];

    /**
     * Item this verification question belongs to.
     *
     * @return BelongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Answers submitted for this question by claimants.
     *
     * @return HasMany<ClaimVerificationAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(ClaimVerificationAnswer::class);
    }
}

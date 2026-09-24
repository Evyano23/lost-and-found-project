<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'type',
        'status',
        'date_lost_or_found',
        'location_name',
        'latitude',
        'longitude',
        'primary_image',
        'contact_phone',
        'contact_email',
        'reward_amount',
        'is_anonymous',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ItemType::class,
            'status' => ItemStatus::class,
            'date_lost_or_found' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
            'reward_amount' => 'decimal:2',
            'is_anonymous' => 'boolean',
        ];
    }

    /**
     * User who reported the item.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category this item belongs to.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Claims made on this item.
     *
     * @return HasMany<Claim, $this>
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Photo gallery of the item.
     *
     * @return HasMany<ItemImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class);
    }

    /**
     * Scope for lost items.
     */
    public function scopeLost(Builder $query): Builder
    {
        return $query->where('type', ItemType::LOST);
    }

    /**
     * Scope for found items.
     */
    public function scopeFound(Builder $query): Builder
    {
        return $query->where('type', ItemType::FOUND);
    }

    /**
     * Scope for active items.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ItemStatus::ACTIVE);
    }

    /**
     * Get display name for reporter based on anonymity setting.
     */
    public function reporterName(): string
    {
        return $this->is_anonymous ? 'Anonymous Member' : ($this->user?->name ?? 'Community Member');
    }

    /**
     * Determine if this item can accept claims.
     */
    public function canBeClaimed(): bool
    {
        return in_array($this->status, [ItemStatus::ACTIVE, ItemStatus::PENDING_CLAIM]);
    }

    /**
     * Mark item as claimed.
     */
    public function markAsClaimed(): void
    {
        $this->update(['status' => ItemStatus::CLAIMED]);
    }

    /**
     * Mark item as resolved.
     */
    public function markAsResolved(): void
    {
        $this->update(['status' => ItemStatus::RESOLVED]);
        // Increment successful returns count for the owner when an item is finally resolved
        if ($this->user) {
            $this->user->increment('successful_returns_count');
        }
    }

    /**
     * Reports made against this item.
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}

<?php

namespace App\Enums;

enum ItemStatus: string
{
    case ACTIVE = 'active';
    case PENDING_CLAIM = 'pending_claim';
    case CLAIMED = 'claimed';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::PENDING_CLAIM => 'Claim Pending',
            self::CLAIMED => 'Claimed',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
        };
    }
}

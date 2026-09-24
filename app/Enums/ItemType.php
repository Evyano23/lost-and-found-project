<?php

namespace App\Enums;

enum ItemType: string
{
    case LOST = 'lost';
    case FOUND = 'found';

    public function label(): string
    {
        return match ($this) {
            self::LOST => 'Lost',
            self::FOUND => 'Found',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::LOST => 'red',
            self::FOUND => 'emerald',
        };
    }
}

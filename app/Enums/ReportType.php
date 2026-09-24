<?php

namespace App\Enums;

enum ReportType: string
{
    case FAKE_ITEM = 'fake_item';
    case INAPPROPRIATE_CONTENT = 'inappropriate_content';
    case SUSPICIOUS_CLAIM = 'suspicious_claim';
    case SPAM = 'spam';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::FAKE_ITEM => 'Fake Item',
            self::INAPPROPRIATE_CONTENT => 'Inappropriate Content',
            self::SUSPICIOUS_CLAIM => 'Suspicious Claim',
            self::SPAM => 'Spam',
            self::OTHER => 'Other',
        };
    }
}

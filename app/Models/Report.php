<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'reportable_id',
        'reportable_type',
        'reporter_id',
        'type',
        'reason',
        'status',
        'is_valid',
    ];

    protected $casts = [
        'type' => ReportType::class,
        'status' => ReportStatus::class,
        'is_valid' => 'boolean',
    ];

    /**
     * Get the owning reportable model.
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who submitted the report.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimVerificationAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'verification_question_id',
        'answer',
    ];

    /**
     * Claim this answer belongs to.
     *
     * @return BelongsTo<Claim, $this>
     */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Verification question this answer is answering.
     *
     * @return BelongsTo<VerificationQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(VerificationQuestion::class, 'verification_question_id');
    }
}

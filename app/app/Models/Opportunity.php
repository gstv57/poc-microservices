<?php

namespace App\Models;

use App\Enum\OpportunityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Opportunity extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'details',
        'business',
        'last_send_at',
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => OpportunityStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function isPending(): bool
    {
        return $this->status === OpportunityStatus::PENDING;
    }
}

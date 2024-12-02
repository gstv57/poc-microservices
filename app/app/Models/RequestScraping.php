<?php

namespace App\Models;

use App\Enum\RequestScrapingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class RequestScraping extends Model
{
    use HasFactory;

    protected $fillable = ['query', 'user_id', 'hash', 'status'];

    protected $casts = [
        'status' => RequestScrapingStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function opportunities(): BelongsToMany
    {
        return $this->belongsToMany(Opportunity::class, 'opportunity_request_scraping', 'request_scraping_id', 'opportunity_id');
    }
    protected static function booted(): void
    {
        static::creating(function (RequestScraping $requestScraping) {
            $requestScraping->status = RequestScrapingStatus::PENDING;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'hash';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $fillable = [
        'title',
        'url',
        'details',
        'business',
        'last_send_at',
    ];
}

<?php

namespace App\Enum;

enum OpportunityStatus: string
{
    case PENDING  = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}

<?php

namespace App\Enum;

enum RequestScrapingStatus: string
{
    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case FAILED     = 'failed';
    case CANCELLED  = 'cancelled';
    case DONE       = 'done';
}

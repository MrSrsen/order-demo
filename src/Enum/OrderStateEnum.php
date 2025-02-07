<?php

namespace App\Enum;

enum OrderStateEnum: string
{
    case New = 'new';

    case Processing = 'processing';

    case Dispatched = 'dispatched';

    case Cancelled = 'cancelled';

    case Fulfilled = 'fulfilled';
}

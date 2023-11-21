<?php

namespace App\Enums;

enum TicketStatusEnum:int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
    case PENDING = -1;
}

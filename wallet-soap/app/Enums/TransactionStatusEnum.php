<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Expired = 'expired';
}

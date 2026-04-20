<?php

namespace App\Enums;

enum HostApplicationStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
}

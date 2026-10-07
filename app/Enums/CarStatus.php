<?php

namespace App\Enums;

enum CarStatus: string
{
    case Available = 'available';
    case Rented = 'rented';
    case Maintenance = 'maintenance';
}


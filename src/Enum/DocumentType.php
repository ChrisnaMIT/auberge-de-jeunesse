<?php

namespace App\Enum;

enum DocumentType: string
{
    case PASSPORT = 'passport';
    case ID_CARD = 'id_card';
    case DRIVER_LICENSE = 'driver_license';
}

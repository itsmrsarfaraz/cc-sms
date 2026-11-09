<?php

namespace App\Enums;

enum InstitutionStatus: string
{
    case Trial = 'trial';
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';
}
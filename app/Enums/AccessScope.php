<?php

namespace App\Enums;

enum AccessScope: string
{
    case Platform = 'platform';
    case Institution = 'institution';
}
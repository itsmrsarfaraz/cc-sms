<?php

namespace App\Enums;

enum InstitutionType: string
{
    case School = 'school';
    case College = 'college';
    case Academy = 'academy';
    case Other = 'other';
}
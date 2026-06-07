<?php

namespace App\Enums;

enum ProjectRole: string
{
    case OWNER = 'owner';
    case MEMBER = 'member';
}

<?php

declare(strict_types=1);

namespace App\Domains\Status\Enums;

use App\Common\Traits\PrepareEnumDataMethods;

enum StatusType: string
{
    use PrepareEnumDataMethods;

    case CASE = 'Case';
    case ASSIGNMENT = 'Assignment';

}

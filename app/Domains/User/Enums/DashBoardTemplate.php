<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use App\Domains\User\Traits\PrepareEnumDataMethods;

enum DashBoardTemplate: string
{
    use PrepareEnumDataMethods;

    case DEFAULT = 'DefaultDashboard';

}

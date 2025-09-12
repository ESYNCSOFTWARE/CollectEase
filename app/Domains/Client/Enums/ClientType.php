<?php

declare(strict_types=1);

namespace App\Domains\Client\Enums;

use App\Common\Traits\PrepareEnumDataMethods;

enum ClientType: string
{
    use PrepareEnumDataMethods;

    case BANK = 'Bank';
    case NBFC = 'NBFC';
    case COMPANY = 'Company';
    case OTHER = 'Other';

}

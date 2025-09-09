<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use App\Domains\User\Traits\PrepareEnumDataMethods;

enum DashboardTemplateType: string
{
    use PrepareEnumDataMethods;

    case SUPER_ADMIN = 'SuperAdminDashboard';
    case AGENT = 'AgentDashboard';
    case DEFAULT = 'DefaultDashboard';

}

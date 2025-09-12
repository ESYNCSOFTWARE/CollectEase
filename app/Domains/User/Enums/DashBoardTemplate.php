<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use App\Domains\User\Traits\PrepareEnumDataMethods;

enum DashBoardTemplate: string
{
    use PrepareEnumDataMethods;

    case DEFAULT_DASHBOARD = 'DefaultDashboard';
    case SUPER_ADMIN_DASHBOARD='SuperAdminDashboard';
    case AGENT_DASHBOARD='AgentDashboard';

}

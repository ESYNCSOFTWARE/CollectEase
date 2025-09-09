<?php

declare(strict_types=1);

namespace App\Domains\Permission\Enums;

enum PermissionList: string
{
    case VIEW = 'VIEW';
    case CREATE = 'CREATE';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
    case EXPORT = 'EXPORT';

    public static function getExportPermissionName(string $moduleName): string
    {
        return $moduleName.'_'.self::EXPORT->value;
    }

    public static function getViewPermissionName(string $moduleName): string
    {
        return $moduleName.'_'.self::VIEW->value;
    }

    public static function getCreatePermissionName(string $moduleName): string
    {
        return $moduleName.'_'.self::CREATE->value;
    }

    public static function getUpdatePermissionName(string $moduleName): string
    {
        return $moduleName.'_'.self::UPDATE->value;
    }

    public static function getDeletePermissionName(string $moduleName): string
    {
        return $moduleName.'_'.self::DELETE->value;
    }
}

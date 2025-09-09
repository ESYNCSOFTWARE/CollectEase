<?php

declare(strict_types=1);

namespace App\Domains\Role\DataObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class AssignPermissionToRoleData extends Data
{
    public function __construct(
        public int $permission_id,
        public int $role_id,
        public bool $is_permission,

    ) {}

    public static function rules(Request $request): array
    {
        return [
            'permission_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
            'is_permission' => ['required', 'boolean'],
        ];
    }
}

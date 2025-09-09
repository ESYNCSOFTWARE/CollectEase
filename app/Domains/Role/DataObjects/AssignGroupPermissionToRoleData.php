<?php

declare(strict_types=1);

namespace App\Domains\Role\DataObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class AssignGroupPermissionToRoleData extends Data
{
    public function __construct(
        public int $permission_group_id,
        public int $role_id,

    ) {}

    public static function rules(Request $request): array
    {
        return [
            'permission_group_id' => ['sometimes', 'integer'],
            'role_id' => ['required', 'integer'],
        ];
    }
}

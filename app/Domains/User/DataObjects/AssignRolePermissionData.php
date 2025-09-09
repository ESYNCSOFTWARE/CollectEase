<?php

declare(strict_types=1);

namespace App\Domains\User\DataObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class AssignRolePermissionData extends Data
{
    public function __construct(
        public array $role_ids,

    ) {}

    public static function rules(Request $request): array
    {
        return [
            'role_ids' => ['required', 'array'],
        ];
    }
}

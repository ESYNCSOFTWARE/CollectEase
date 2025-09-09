<?php

declare(strict_types=1);

namespace App\Domains\Permission\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PermissionGroupData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description,
    ) {}

    public static function rules(Request $request): array
    {

        $permissionGroupId = null;

        if ($request->route()?->getName() === 'permission_groups.update') {
            $permissionGroupId = $request->route()->parameter('permissionGroupId');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permission_groups', 'name')->ignore($permissionGroupId),
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}

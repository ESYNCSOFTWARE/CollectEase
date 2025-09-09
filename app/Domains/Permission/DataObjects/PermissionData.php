<?php

declare(strict_types=1);

namespace App\Domains\Permission\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PermissionData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description,
        public string $display_name,

    ) {}

    public static function rules(Request $request): array
    {

        $permissionId = null;

        if ($request->route()?->getName() === 'permissions.update') {
            $permissionId = $request->route()->parameter('permissionId');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
            'display_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'display_name')->ignore($permissionId),
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

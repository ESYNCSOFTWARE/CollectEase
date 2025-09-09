<?php

declare(strict_types=1);

namespace App\Domains\Role\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class RoleData extends Data
{
    public function __construct(
        public string $name,
    ) {}

    public static function rules(Request $request): array
    {
        $roleId = null;

        if ($request->route()?->getName() === 'roles.update') {
            $roleId = $request->route()->parameter('roleId');
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
        ];
    }
}

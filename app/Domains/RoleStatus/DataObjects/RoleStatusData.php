<?php

declare(strict_types=1);

namespace App\Domains\RoleStatus\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class RoleStatusData extends Data
{
    public function __construct(
        public int $role_id,
        public int $status_id,
        public bool $can_view,
        public bool $can_update,

    ) {}

    public static function rules(Request $request): array
    {

        $regionId = null;
        if ($request->route()?->getName() === 'regions.update') {
            $regionId = $request->route()->parameter('regionId');
        }

       return [
            'role_id' => [
                'required',
                'integer',
            ],
            'status_id' => [
                'required',
                'integer',
            ],
            'can_view' => [
                'required',
                'boolean',
            ],
            'can_update' => [
                'required',
                'boolean',
            ],


        ];
    }
}

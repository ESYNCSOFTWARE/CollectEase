<?php

declare(strict_types=1);

namespace App\Domains\Permission\DataObjects;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class AssignPermissionToGroupData extends Data
{
    public function __construct(
        public array $permission_ids,
    ) {}

    public static function rules(Request $request): array
    {
        return [
            'permission_ids' => ['required', 'array'],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Domains\Status\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Domains\Status\Enums\StatusType;
use Spatie\LaravelData\Data;

class StatusData extends Data
{
    public function __construct(
        public string $name,
        public string $type,
        public string $code,
        public string $description,
        public string $color,
        public int $sort_order,
        public bool $is_default,
    ) {
    }

    public static function rules(Request $request): array
    {

        $statusId = null;
        if ($request->route()?->getName() === 'statuses.update') {
            $statusId = $request->route()->parameter('statusId');
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'type' => [
                'required',
                'in:'.StatusType::getValues(),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
            ],
            'description' => [
                'required',
                'string',
                'max:1000',
            ],
            'color' => [
                'required',
                'string',
                'max:50',
            ],
            'sort_order' => [
                'required',
                'integer',
            ],

        ];

        return $rules;
    }
}
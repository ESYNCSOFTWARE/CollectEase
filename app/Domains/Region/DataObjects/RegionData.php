<?php

declare(strict_types=1);

namespace App\Domains\Region\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class RegionData extends Data
{
    public function __construct(
        public string $name,
        public string $code,
    ) {}

    public static function rules(Request $request): array
    {

        $regionId = null;
        if ($request->route()?->getName() === 'regions.update') {
            $regionId = $request->route()->parameter('regionId');
        }

        // Define basic validation rules
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'max:255',
            ],
        
        ];

        return $rules;
    }
}

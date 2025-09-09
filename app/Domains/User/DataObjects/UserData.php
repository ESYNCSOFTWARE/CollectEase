<?php

declare(strict_types=1);

namespace App\Domains\User\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
        public string $dashboard_component,
    ) {}

    public static function rules(Request $request): array
    {

        $userId = null;
        if ($request->route()?->getName() === 'users.update') {
            $userId = $request->route()->parameter('userId');
        }

        // Define basic validation rules
        $rules = [
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
              'dashboard_component' => [
                'required',
                'string',
                'max:255',
            ],
        ];

        if (! empty($request->password) && $request->route()?->getName() === 'users.store') {

            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ];

            $rules['password_confirmation'] = [
                'required_with:password',
                'same:password',
            ];
        }

        return $rules;
    }
}

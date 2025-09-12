<?php

declare(strict_types=1);

namespace App\Domains\Client\DataObjects;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ClientData extends Data
{
    public function __construct(
        public string $name,
        public string $code,
        public string $type,
        public bool $status,
        public ?string $email,
        public ?string $contact_person,
        public ?string $address,
        public ?string $phone,

    ) {}

    public static function rules(Request $request): array
    {

        $clientId = null;
        if ($request->route()?->getName() === 'clients.update') {
            $clientId = $request->route()->parameter('clientId');
        }

        return [
            'email' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'name')->ignore($clientId),
            ],
            'type' => [
                'required',
                'string',
                'max:40',
            ],
            'contact_person' => [
                'nullable',
                'string',
                'max:40',
            ],
            'address' => [
                'nullable',
                'string',
                'max:40',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:40',
            ],
        ];
    }
}

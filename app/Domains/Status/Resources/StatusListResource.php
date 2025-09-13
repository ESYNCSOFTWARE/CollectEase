<?php

declare(strict_types=1);

namespace App\Domains\Status\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class StatusListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $status = $this->resource;

        return [
            'id' => $status->id,
            'name' => $status->name,
            'type' => $status->type,
            'code' => $status->code,
            'description' => $status->description,
            'color' => $status->color,
            'sort_order' => $status->sort_order,
            'is_default' => $status->is_default,
        ];
    }
}
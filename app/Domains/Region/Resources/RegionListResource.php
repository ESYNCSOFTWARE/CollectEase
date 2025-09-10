<?php

declare(strict_types=1);

namespace App\Domains\Region\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class RegionListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $region = $this->resource;

        return [
            'id' => $region->id,
            'name' => $region->name,
            'code' => $region->code,
            'status' => $region->status,
        ];
    }
}

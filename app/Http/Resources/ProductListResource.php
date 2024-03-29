<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => "{$this->name} - {$this->material} - {$this->stock_meters}",
            "unit_price_in_dollars" => $this->unit_price_in_dollars
        ];
    }
}

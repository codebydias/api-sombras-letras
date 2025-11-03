<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'serie_id' => $this->serie_id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'price' => (float) $this->price,
            'sale_price' => (float) $this->sale_price,
        ];
    }
}

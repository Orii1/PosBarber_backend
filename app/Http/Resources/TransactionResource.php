<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'total_price' => $this->total_price,

            'services' => $this->details->map(function ($detail) {
                return [
                    'id' => $detail->service->id,
                    'name' => $detail->service->name,
                    'price' => $detail->price,
                ];
            }),
        ];
    }
}

<?php

namespace Molitor\Customer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Customer',
    title: 'Customer',
    description: 'Customer information',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'internal_name', type: 'string', example: 'john_doe'),
        new OA\Property(property: 'is_seller', type: 'boolean', example: false),
        new OA\Property(property: 'is_buyer', type: 'boolean', example: true),
        new OA\Property(property: 'description', type: 'string', example: 'VIP customer'),
        new OA\Property(property: 'tax_number', type: 'string', example: 'XX123456789'),
        new OA\Property(property: 'customer_group_id', type: 'integer', nullable: true),
        new OA\Property(property: 'currency_id', type: 'integer', nullable: true),
        new OA\Property(property: 'language_id', type: 'integer', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'internal_name' => $this->internal_name,
            'is_seller' => $this->is_seller,
            'is_buyer' => $this->is_buyer,
            'description' => $this->description,
            'tax_number' => $this->tax_number,
            'customer_group_id' => $this->customer_group_id,
            'currency_id' => $this->currency_id,
            'language_id' => $this->language_id,
            'invoice_address_id' => $this->invoice_address_id,
            'shipping_address_id' => $this->shipping_address_id,
            'customer_group' => CustomerGroupSimpleResource::make($this->whenLoaded('customerGroup')),
            'currency' => $this->whenLoaded('currency', function () {
                return ['id' => $this->currency?->id, 'name' => $this->currency?->name];
            }),
            'language' => $this->whenLoaded('language', function () {
                return ['id' => $this->language?->id, 'name' => $this->language?->name];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

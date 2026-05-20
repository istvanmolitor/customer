<?php

namespace Molitor\Customer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateCustomerRequest',
    title: 'Update Customer Request',
    description: 'Data for updating a customer',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'internal_name', type: 'string', example: 'john_doe', nullable: true),
        new OA\Property(property: 'is_seller', type: 'boolean', example: false),
        new OA\Property(property: 'is_buyer', type: 'boolean', example: true),
        new OA\Property(property: 'description', type: 'string', example: 'VIP customer'),
        new OA\Property(property: 'customer_group_id', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'currency_id', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'language_id', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'tax_number', type: 'string', example: 'XX123456789', nullable: true),
    ]
)]
class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'internal_name' => 'nullable|string|max:255',
            'is_seller' => 'boolean',
            'is_buyer' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'customer_group_id' => 'nullable|exists:customer_groups,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'language_id' => 'nullable|exists:languages,id',
            'tax_number' => 'nullable|string|max:50',
        ];
    }
}

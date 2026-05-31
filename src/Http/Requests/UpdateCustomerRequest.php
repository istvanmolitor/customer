<?php

namespace Molitor\Customer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
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
        new OA\Property(property: 'user_id', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'currency_id', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'language_id', type: 'integer', example: 1, nullable: true),
        new OA\Property(property: 'tax_number', type: 'string', example: 'XX123456789', nullable: true),
        new OA\Property(property: 'invoice_address', type: 'object', nullable: true),
        new OA\Property(property: 'shipping_address', type: 'object', nullable: true),
    ]
)]
class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('acl', 'customer');
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
            'user_id' => 'nullable|exists:users,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'language_id' => 'nullable|exists:languages,id',
            'tax_number' => 'nullable|string|max:50',
            'invoice_address' => 'nullable|array',
            'invoice_address.name' => 'nullable|string|max:255',
            'invoice_address.country_id' => 'nullable|exists:countries,id',
            'invoice_address.zip_code' => 'nullable|string|max:10',
            'invoice_address.city' => 'nullable|string|max:255',
            'invoice_address.address' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|array',
            'shipping_address.name' => 'nullable|string|max:255',
            'shipping_address.country_id' => 'nullable|exists:countries,id',
            'shipping_address.zip_code' => 'nullable|string|max:10',
            'shipping_address.city' => 'nullable|string|max:255',
            'shipping_address.address' => 'nullable|string|max:255',
        ];
    }
}

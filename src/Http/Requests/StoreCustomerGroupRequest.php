<?php

namespace Molitor\Customer\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreCustomerGroupRequest',
    title: 'Store Customer Group Request',
    description: 'Data for creating a customer group',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Premium Customers'),
        new OA\Property(property: 'description', type: 'string', example: 'High value customers with special pricing'),
    ]
)]
class StoreCustomerGroupRequest extends FormRequest
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
            'description' => 'nullable|string|max:1000',
        ];
    }
}

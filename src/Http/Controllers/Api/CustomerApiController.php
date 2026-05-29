<?php

namespace Molitor\Customer\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\Currency\Models\Currency;
use Molitor\Customer\Http\Requests\StoreCustomerRequest;
use Molitor\Customer\Http\Requests\UpdateCustomerRequest;
use Molitor\Customer\Http\Resources\CustomerGroupSimpleResource;
use Molitor\Customer\Http\Resources\CustomerResource;
use Molitor\Customer\Models\Customer;
use Molitor\Customer\Models\CustomerGroup;
use Molitor\Customer\Repositories\CustomerRepositoryInterface;
use Molitor\Language\Models\Language;
use OpenApi\Attributes as OA;

class CustomerApiController extends Controller
{
    use HasAdminFilters;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    #[OA\Get(
        path: '/api/admin/customer/customers',
        summary: 'List all customers',
        tags: ['Customers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Customer')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Customer::with('customerGroup', 'user', 'currency', 'language');
        $customers = $this->applyAdminFilters($query, $request, ['name', 'internal_name', 'tax_number'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => CustomerResource::collection($customers->items()),
            'meta' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/customer/customers/create',
        summary: 'Show form for creating a customer',
        tags: ['Customers'],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
        ]
    )]
    public function create(): JsonResponse
    {
        return response()->json([
            'customer_groups' => CustomerGroupSimpleResource::collection(CustomerGroup::all()),
            'currencies' => Currency::all()->pluck('name', 'id'),
            'languages' => Language::all()->pluck('name', 'id'),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/customer/customers',
        summary: 'Store a new customer',
        tags: ['Customers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreCustomerRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Customer'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $customer = $this->customerRepository->create($validated);

        $this->persistAddresses($customer, $validated);

        $customer->load('customerGroup', 'user', 'currency', 'language', 'invoiceAddress', 'shippingAddress');

        return response()->json([
            'data' => new CustomerResource($customer),
            'message' => __('customer::messages.created'),
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/customer/customers/{customer}',
        summary: 'Display a specific customer',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Customer'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Customer $customer): JsonResponse
    {
        $customer->load('customerGroup', 'user', 'currency', 'language', 'invoiceAddress', 'shippingAddress');

        return response()->json([
            'data' => new CustomerResource($customer),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/customer/customers/{customer}/edit',
        summary: 'Show form for editing a customer',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function edit(Customer $customer): JsonResponse
    {
        $customer->load('customerGroup', 'user', 'currency', 'language', 'invoiceAddress', 'shippingAddress');

        return response()->json([
            'data' => new CustomerResource($customer),
            'customer_groups' => CustomerGroupSimpleResource::collection(CustomerGroup::all()),
            'currencies' => Currency::all()->pluck('name', 'id'),
            'languages' => Language::all()->pluck('name', 'id'),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/customer/customers/{customer}',
        summary: 'Update a customer',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateCustomerRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Customer'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $validated = $request->validated();

        $customer->update([
            'name' => $validated['name'],
            'internal_name' => $validated['internal_name'] ?? $validated['name'],
            'is_seller' => $validated['is_seller'] ?? $customer->is_seller,
            'is_buyer' => $validated['is_buyer'] ?? $customer->is_buyer,
            'description' => $validated['description'] ?? null,
            'customer_group_id' => $validated['customer_group_id'] ?? $customer->customer_group_id,
            'user_id' => $validated['user_id'] ?? null,
            'currency_id' => $validated['currency_id'] ?? $customer->currency_id,
            'language_id' => $validated['language_id'] ?? $customer->language_id,
            'tax_number' => $validated['tax_number'] ?? null,
        ]);

        $this->persistAddresses($customer, $validated);

        $customer->load('customerGroup', 'user', 'currency', 'language', 'invoiceAddress', 'shippingAddress');

        return response()->json([
            'data' => new CustomerResource($customer),
            'message' => __('customer::messages.updated'),
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/customer/customers/{customer}',
        summary: 'Delete a customer',
        tags: ['Customers'],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => __('customer::messages.deleted'),
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function persistAddresses(Customer $customer, array $validated): void
    {
        $addressRepository = app(AddressRepositoryInterface::class);

        if (array_key_exists('invoice_address', $validated) && $customer->invoiceAddress !== null) {
            $addressRepository->saveAddress($customer->invoiceAddress, (array) ($validated['invoice_address'] ?? []));
        }

        if (array_key_exists('shipping_address', $validated) && $customer->shippingAddress !== null) {
            $addressRepository->saveAddress($customer->shippingAddress, (array) ($validated['shipping_address'] ?? []));
        }
    }
}

<?php

namespace Molitor\Customer\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\Customer\Http\Requests\StoreCustomerGroupRequest;
use Molitor\Customer\Http\Requests\UpdateCustomerGroupRequest;
use Molitor\Customer\Http\Resources\CustomerGroupResource;
use Molitor\Customer\Models\CustomerGroup;
use OpenApi\Attributes as OA;

class CustomerGroupApiController extends Controller
{
    use HasAdminFilters;

    #[OA\Get(
        path: '/api/admin/customer/customer-groups',
        summary: 'List all customer groups',
        tags: ['Customer Groups'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/CustomerGroup')
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
        $query = CustomerGroup::query();
        $customerGroups = $this->applyAdminFilters($query, $request, ['name', 'description'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => CustomerGroupResource::collection($customerGroups->items()),
            'meta' => [
                'current_page' => $customerGroups->currentPage(),
                'last_page' => $customerGroups->lastPage(),
                'per_page' => $customerGroups->perPage(),
                'total' => $customerGroups->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/customer/customer-groups/create',
        summary: 'Show form for creating a customer group',
        tags: ['Customer Groups'],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
        ]
    )]
    public function create(): JsonResponse
    {
        return response()->json([]);
    }

    #[OA\Post(
        path: '/api/admin/customer/customer-groups',
        summary: 'Store a new customer group',
        tags: ['Customer Groups'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreCustomerGroupRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CustomerGroup'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreCustomerGroupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $customerGroup = CustomerGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'data' => new CustomerGroupResource($customerGroup),
            'message' => __('customer::messages.group_created'),
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/customer/customer-groups/{customerGroup}',
        summary: 'Display a specific customer group',
        tags: ['Customer Groups'],
        parameters: [
            new OA\Parameter(name: 'customerGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CustomerGroup'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(CustomerGroup $customerGroup): JsonResponse
    {
        return response()->json([
            'data' => new CustomerGroupResource($customerGroup),
        ]);
    }

    #[OA\Get(
        path: '/api/admin/customer/customer-groups/{customerGroup}/edit',
        summary: 'Show form for editing a customer group',
        tags: ['Customer Groups'],
        parameters: [
            new OA\Parameter(name: 'customerGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function edit(CustomerGroup $customerGroup): JsonResponse
    {
        return response()->json([
            'data' => new CustomerGroupResource($customerGroup),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/customer/customer-groups/{customerGroup}',
        summary: 'Update a customer group',
        tags: ['Customer Groups'],
        parameters: [
            new OA\Parameter(name: 'customerGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateCustomerGroupRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/CustomerGroup'),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup): JsonResponse
    {
        $validated = $request->validated();

        $customerGroup->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'data' => new CustomerGroupResource($customerGroup),
            'message' => __('customer::messages.group_updated'),
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/customer/customer-groups/{customerGroup}',
        summary: 'Delete a customer group',
        tags: ['Customer Groups'],
        parameters: [
            new OA\Parameter(name: 'customerGroup', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(CustomerGroup $customerGroup): JsonResponse
    {
        $customerGroup->delete();

        return response()->json([
            'message' => __('customer::messages.group_deleted'),
        ]);
    }
}

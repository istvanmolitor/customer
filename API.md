# Customer Package

Backend API for managing customers and customer groups.

## Features

- REST API endpoints for customer management
- Customer groups support
- Integration with Currency and Language packages
- Address management (invoice and shipping)
- Customer repositories pattern
- Form request validation
- API Resources for data transformation

## Models

- **Customer**: Main customer model with relationships to groups, users, currencies, languages, and addresses
- **CustomerGroup**: Customer grouping for categorization

## API Endpoints

All endpoints require authentication (`auth:sanctum`) and are prefixed with `/api/admin/customer`:

### Customers

- `GET /customers` - List all customers (paginated)
- `GET /customers/create` - Get form data for creating a customer
- `POST /customers` - Create a new customer
- `GET /customers/{customer}` - Get a specific customer
- `GET /customers/{customer}/edit` - Get customer with form data for editing
- `PUT /customers/{customer}` - Update a customer
- `DELETE /customers/{customer}` - Delete a customer

### Customer Groups

- `GET /customer-groups` - List all customer groups (paginated)
- `GET /customer-groups/create` - Get form data for creating a group
- `POST /customer-groups` - Create a new customer group
- `GET /customer-groups/{customerGroup}` - Get a specific customer group
- `GET /customer-groups/{customerGroup}/edit` - Get group with form data for editing
- `PUT /customer-groups/{customerGroup}` - Update a customer group
- `DELETE /customer-groups/{customerGroup}` - Delete a customer group

## Controllers

- **CustomerApiController**: Handles all customer CRUD operations
- **CustomerGroupApiController**: Handles all customer group CRUD operations

## Requests (Validation)

- **StoreCustomerRequest**: Validation for creating customers
- **UpdateCustomerRequest**: Validation for updating customers
- **StoreCustomerGroupRequest**: Validation for creating groups
- **UpdateCustomerGroupRequest**: Validation for updating groups

## Resources

- **CustomerResource**: Transforms customer data for API responses
- **CustomerGroupResource**: Transforms customer group data for API responses
- **CustomerGroupSimpleResource**: Simplified customer group data (for dropdowns)

## Repositories

- **CustomerRepository**: Implements `CustomerRepositoryInterface` for database operations
- Methods: `getByName()`, `getByInternalName()`, `findOrCreate()`, `getOptions()`, `delete()`, `getAll()`, `getById()`, `getSellerOptions()`, `getByUser()`

## Integration

The package is auto-discovered via Laravel's service provider system. Routes are loaded automatically in the service provider.

## Dependencies

- `istvanmolitor/language`
- `istvanmolitor/currency`
- `laravel/sanctum`

## Service Provider

The `CustomerServiceProvider` automatically:
- Loads database migrations
- Registers repositories
- Loads API routes
- Loads translation files


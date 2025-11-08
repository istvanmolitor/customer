<?php

declare(strict_types=1);

namespace Molitor\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Molitor\Address\Models\Address;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Currency\Models\Currency;
use Molitor\Customer\database\factories\CustomerFactory;
use Molitor\Language\Models\Language;
use Molitor\User\Models\User;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'internal_name',
        'is_seller',
        'is_buyer',
        'user_id',
        'description',
        'customer_group_id',
        'currency_id',
        'language_id',
        'invoice_address_id',
        'shipping_address_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if(empty($customer->invoice_address_id)) {
                $customer->invoice_address_id = app(AddressRepositoryInterface::class)->createEmptyId();
            }
            if(empty($customer->shipping_address_id)) {
                $customer->shipping_address_id = app(AddressRepositoryInterface::class)->createEmptyId();
            }
        });
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function invoiceAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'invoice_address_id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }


}

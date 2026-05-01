<?php

namespace Molitor\Customer\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Molitor\Address\Filament\Components\Address;
use Molitor\Currency\Repositories\CurrencyRepositoryInterface;
use Molitor\Customer\Filament\Resources\CustomerResource\Pages;
use Molitor\Customer\Models\Customer;
use Molitor\Language\Repositories\LanguageRepositoryInterface;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationGroup(): string
    {
        return __('customer::customer.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('customer::customer.title');
    }

    public static function canAccess(): bool
    {
        return Gate::allows('acl', 'customer');
    }

    public static function form(Schema $schema): Schema
    {
        /** @var CurrencyRepositoryInterface $currencyRepository */
        $currencyRepository = app(CurrencyRepositoryInterface::class);
        /** @var LanguageRepositoryInterface $languageRepository */
        $languageRepository = app(LanguageRepositoryInterface::class);

        return $schema->components([

            Tabs::make('customer_tabs')->tabs([
                Tabs\Tab::make('general')->label(__('customer::common.general'))->components([
                    TextInput::make('name')->label(__('customer::common.name'))->required()->maxLength(255),
                    TextInput::make('internal_name')->label(__('customer::common.internal_name'))->maxLength(255),
                    Textarea::make('description')->label(__('customer::common.description'))->columnSpanFull(),
                ]),
                Tabs\Tab::make('meta')->label(__('customer::common.detailed'))->components([
                    Toggle::make('is_seller')->label(__('customer::common.is_seller'))->default(false),
                    Toggle::make('is_buyer')->label(__('customer::common.is_buyer'))->default(false),

                    Select::make('user_id')
                        ->label(__('customer::common.user'))
                        ->relationship(name: 'user', titleAttribute: 'name')
                        ->searchable()
                        ->preload(),

                    TextInput::make('tax_number')
                        ->label(__('customer::common.tax_number'))
                        ->maxLength(50),

                    Select::make('customer_group_id')
                        ->label(__('customer::common.group'))
                        ->relationship(name: 'customerGroup', titleAttribute: 'name')
                        ->searchable()
                        ->preload(),
                    Select::make('currency_id')
                        ->label(__('customer::common.currency'))
                        ->relationship(name: 'currency', titleAttribute: 'code')
                        ->default($currencyRepository->getDefaultId())
                        ->searchable()
                        ->preload(),
                    Select::make('language_id')
                        ->label(__('customer::common.language'))
                        ->relationship(name: 'language', titleAttribute: 'code')
                        ->default($languageRepository->getDefaultId())
                        ->searchable()
                        ->preload(),
                ])->columns(2),
                Tabs\Tab::make('advanced')->label(__('customer::common.addresses'))->components([
                    Address::make('invoice_address', __('customer::common.invoice_address')),
                    Address::make('shipping_address', __('customer::common.shipping_address')),
                ]),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('customer::common.name'))->searchable()->sortable(),
                IconColumn::make('is_seller')
                    ->boolean()
                    ->label(__('customer::common.is_seller')),
                IconColumn::make('is_buyer')
                    ->boolean()
                    ->label(__('customer::common.is_buyer')),
                TextColumn::make('customerGroup.name')->label(__('customer::common.group'))->sortable(),
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}

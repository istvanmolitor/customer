<?php

namespace Molitor\Customer\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
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
                    Forms\Components\TextInput::make('name')->label(__('customer::common.name'))->required()->maxLength(255),
                    Forms\Components\TextInput::make('internal_name')->label(__('customer::common.internal_name'))->maxLength(255),
                    Forms\Components\Textarea::make('description')->label(__('customer::common.description'))->columnSpanFull(),
                ]),
                Tabs\Tab::make('meta')->label(__('customer::common.detailed'))->components([
                    Forms\Components\Toggle::make('is_seller')->label(__('customer::common.is_seller'))->default(false),
                    Forms\Components\Toggle::make('is_buyer')->label(__('customer::common.is_buyer'))->default(false),

                    Forms\Components\Select::make('user_id')
                        ->label(__('customer::common.user'))
                        ->relationship(name: 'user', titleAttribute: 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('customer_group_id')
                        ->label(__('customer::common.group'))
                        ->relationship(name: 'customerGroup', titleAttribute: 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('currency_id')
                        ->label(__('customer::common.currency'))
                        ->relationship(name: 'currency', titleAttribute: 'code')
                        ->default($currencyRepository->getDefaultId())
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('language_id')
                        ->label(__('customer::common.language'))
                        ->relationship(name: 'language', titleAttribute: 'code')
                        ->default($languageRepository->getDefaultId())
                        ->searchable()
                        ->preload(),
                ])->columns(2),
                Tabs\Tab::make('advanced')->label(__('customer::common.addresses'))->components([
                    \Molitor\Address\Filament\Components\Address::make('invoice_address', __('customer::common.invoice_address')),
                    \Molitor\Address\Filament\Components\Address::make('shipping_address', __('customer::common.shipping_address')),
                ]),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('customer::common.name'))->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_seller')
                    ->boolean()
                    ->label(__('customer::common.is_seller')),
                Tables\Columns\IconColumn::make('is_buyer')
                    ->boolean()
                    ->label(__('customer::common.is_buyer')),
                Tables\Columns\TextColumn::make('customerGroup.name')->label(__('customer::common.group'))->sortable(),
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

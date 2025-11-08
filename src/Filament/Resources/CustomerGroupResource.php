<?php

namespace Molitor\Customer\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Molitor\Customer\Filament\Resources\CustomerGroupResource\Pages;
use Molitor\Customer\Models\CustomerGroup;

class CustomerGroupResource extends Resource
{
    protected static ?string $model = CustomerGroup::class;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): string
    {
        return __('customer::customer.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('customer::customer_group.title');
    }

    public static function canAccess(): bool
    {
        return Gate::allows('acl', 'customer');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->label(__('customer::common.name'))
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label(__('customer::common.description'))
                ->maxLength(65535)
                ->columnSpanFull(),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('customer::common.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('customer::common.description'))
                    ->limit(80),
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
            'index' => Pages\ListCustomerGroups::route('/'),
            'create' => Pages\CreateCustomerGroup::route('/create'),
            'edit' => Pages\EditCustomerGroup::route('/{record}/edit'),
        ];
    }
}

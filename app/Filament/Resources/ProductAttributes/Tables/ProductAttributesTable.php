<?php

namespace App\Filament\Resources\ProductAttributes\Tables;

use App\Models\ProductAttribute;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('-', ' ')->title()),
                TextColumn::make('values')
                    ->label('Values')
                    ->state(fn (ProductAttribute $record): string => implode(', ', $record->values ?? []) ?: '—')
                    ->wrap(),
                IconColumn::make('active')->boolean(),
                IconColumn::make('used_for_variations')->label('Variations')->boolean(),
                TextColumn::make('sort_order')->label('Order')->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}

<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('-', ' ')->title())
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray')
                    ->searchable(),
                TextColumn::make('product_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('-', ' ')->title()),
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state, Product $record): string => $record->priceLabel() ?? 'Contact for pricing')
                    ->sortable(),
                TextColumn::make('currency')
                    ->label('Currency')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('sale_price')
                    ->label('Sale price')
                    ->formatStateUsing(fn (mixed $state, Product $record): string => $state !== null ? ($record->formatAmount((float) $state) ?? '—') : '—')
                    ->sortable(),
                TextColumn::make('compare_at_price')
                    ->formatStateUsing(fn (mixed $state, Product $record): string => $state !== null ? ($record->formatAmount((float) $state) ?? '—') : '—')
                    ->sortable(),
                TextColumn::make('stock_status')
                    ->label('Stock')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title())
                    ->color(fn (string $state): string => match ($state) {
                        'in_stock' => 'success',
                        'on_backorder' => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('stock_quantity')
                    ->label('Qty')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('featured')
                    ->boolean(),
                ImageColumn::make('image_path')->disk('public'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')->options([
                    'skincare' => 'Skincare', 'haircare' => 'Haircare', 'body-care' => 'Body care',
                    'makeup' => 'Makeup', 'accessories' => 'Accessories',
                ]),
                SelectFilter::make('product_type')->options(['simple' => 'Simple', 'variable' => 'Variable']),
                SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Published']),
                SelectFilter::make('stock_status')->options([
                    'in_stock' => 'In stock', 'out_of_stock' => 'Out of stock', 'on_backorder' => 'On backorder',
                ]),
                SelectFilter::make('currency')->options(Product::currencyOptions()),
                TernaryFilter::make('featured')->label('Featured'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}

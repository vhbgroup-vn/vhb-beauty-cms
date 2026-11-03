<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('page_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('template')
                    ->badge()
                    ->toggleable(),
                IconColumn::make('published')
                    ->boolean(),
                IconColumn::make('show_in_navigation')
                    ->label('In nav')
                    ->boolean()
                    ->toggleable(),
                ImageColumn::make('hero_image_path')->disk('public'),
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
                SelectFilter::make('page_type')->options([
                    'about' => 'About Us',
                    'contact' => 'Contact',
                    'other' => 'Other',
                ]),
                SelectFilter::make('template')->options([
                    'standard' => 'Standard page',
                    'landing' => 'Landing page',
                    'campaign' => 'Campaign page',
                    'contact' => 'Contact page',
                    'legal' => 'Legal / policy page',
                ]),
                TernaryFilter::make('published')->label('Published'),
                TernaryFilter::make('show_in_navigation')->label('In navigation'),
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

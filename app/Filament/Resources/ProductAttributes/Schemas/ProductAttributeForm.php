<?php

namespace App\Filament\Resources\ProductAttributes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attribute definition')
                    ->description('Create reusable attributes such as Skin type, Shade, Finish, Volume or Scent.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Attribute name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->helperText('Optional. It will be generated from the name when left blank.')
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label('Attribute type')
                            ->options([
                                'select' => 'Select / options',
                                'text' => 'Text value',
                                'color' => 'Color / shade',
                            ])
                            ->default('select')
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0),
                        TagsInput::make('values')
                            ->label('Available values')
                            ->placeholder('Type a value and press Enter')
                            ->helperText('These values appear as suggestions when assigning the attribute to a product.')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Usage')
                    ->schema([
                        Toggle::make('active')
                            ->label('Active')
                            ->default(true),
                        Toggle::make('visible_by_default')
                            ->label('Visible on product pages by default')
                            ->default(true),
                        Toggle::make('used_for_variations')
                            ->label('Available for product variations')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}

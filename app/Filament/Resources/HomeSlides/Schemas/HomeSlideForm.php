<?php

namespace App\Filament\Resources\HomeSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero slide')
                    ->schema([
                        TextInput::make('headline')->required()->columnSpanFull(),
                        Textarea::make('subheadline')->rows(3)->columnSpanFull(),
                        FileUpload::make('image_path')
                            ->label('Banner image')
                            ->image()
                            ->disk('public')
                            ->directory('home-slides')
                            ->imageEditor(),
                        Toggle::make('active')->label('Active')->default(true),
                    ])
                    ->columns(2),
                Section::make('Call to action')
                    ->collapsed()
                    ->schema([
                        TextInput::make('cta_label')->label('Button label'),
                        TextInput::make('cta_url')->label('Button URL')->url(),
                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}

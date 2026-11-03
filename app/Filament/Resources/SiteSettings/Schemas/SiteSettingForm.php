<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Brand')
                    ->schema([
                        TextInput::make('brand_name')->required()->default('VHB Beauty'),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('brand'),
                        Textarea::make('announcement')->rows(3)->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Support contacts')
                    ->schema([
                        TextInput::make('support_email')->email(),
                        TextInput::make('support_phone')->tel(),
                    ])
                    ->columns(2),
                Section::make('Social links')
                    ->collapsed()
                    ->schema([
                        Repeater::make('social_links')
                            ->schema([
                                TextInput::make('label')->required(),
                                TextInput::make('url')->required()->url(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

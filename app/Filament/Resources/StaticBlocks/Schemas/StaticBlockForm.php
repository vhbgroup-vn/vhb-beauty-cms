<?php

namespace App\Filament\Resources\StaticBlocks\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaticBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Static block details')
                    ->description('Create reusable content once and link it from multiple pages, posts or products.')
                    ->schema([
                        TextInput::make('title')->required()->maxLength(255),
                        TextInput::make('slug')
                            ->helperText('Optional. It will be generated from the title when left blank.')
                            ->unique(ignoreRecord: true),
                        Toggle::make('published')->label('Published')->default(false),
                        Textarea::make('excerpt')->label('Internal note / excerpt')->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Reusable content')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Rich content')
                            ->helperText('Use this for standard reusable text and layout content.')
                            ->columnSpanFull(),
                    ]),
                Section::make('HTML content')
                    ->description('Optional raw HTML fragment. This takes priority over Rich content when filled.')
                    ->schema([
                        CodeEditor::make('html_content')
                            ->label('HTML source')
                            ->language(Language::Html)
                            ->helperText('Paste a fragment that belongs inside the page body. Do not include <html>, <head> or <body> wrappers.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

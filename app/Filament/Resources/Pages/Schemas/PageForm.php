<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page basics')
                    ->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Optional. Generated from the page title when left blank.'),
                        Select::make('page_type')
                            ->options(['about' => 'About Us', 'contact' => 'Contact', 'other' => 'Other'])
                            ->default('other')
                            ->required(),
                        Select::make('template')
                            ->options([
                                'standard' => 'Standard page',
                                'landing' => 'Landing page',
                                'campaign' => 'Campaign page',
                                'contact' => 'Contact page',
                                'legal' => 'Legal / policy page',
                            ])
                            ->default('standard')
                            ->required(),
                        Toggle::make('published')->label('Published'),
                        Toggle::make('show_in_navigation')->label('Show in navigation'),
                        TextInput::make('navigation_label')->label('Navigation label'),
                        TextInput::make('sort_order')->numeric()->default(0),
                        FileUpload::make('hero_image_path')
                            ->label('Hero image')
                            ->image()
                            ->disk('public')
                            ->directory('pages'),
                    ])
                    ->columns(2),
                Section::make('Page content')
                    ->schema([
                        Textarea::make('excerpt')->label('Intro / excerpt')->rows(3)->columnSpanFull(),
                        RichEditor::make('content')->columnSpanFull(),
                    ]),
                Section::make('HTML import')
                    ->description('Paste either a body fragment or a complete HTML document. Complete documents render in an isolated frame so their head/body styles do not collide with the CMS Header and Footer.')
                    ->schema([
                        CodeEditor::make('html_content')
                            ->label('HTML source (fragment or full document)')
                            ->language(Language::Html)
                            ->helperText('You may paste a complete .html file including <!doctype html>, <head> and <body>. The site Header and Footer remain outside it. For a fragment, paste only the content that belongs inside <body>. This field takes priority over the Rich Editor and flexible sections.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Flexible sections')
                    ->description('Compose richer landing pages with reusable hero, text, image, CTA or FAQ sections.')
                    ->schema([
                        Repeater::make('blocks')
                            ->label('Page sections')
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'hero' => 'Hero',
                                        'text' => 'Text section',
                                        'image' => 'Image section',
                                        'cta' => 'Call to action',
                                        'faq' => 'FAQ',
                                    ])
                                    ->default('text')
                                    ->required(),
                                TextInput::make('heading')->label('Heading'),
                                RichEditor::make('body')->label('Body')->columnSpanFull(),
                                FileUpload::make('image_path')
                                    ->label('Section image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/sections')
                                    ->maxSize(5120),
                                TextInput::make('button_label')->label('Button label'),
                                TextInput::make('button_url')->label('Button URL')->url(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? ($state['type'] ?? null))
                            ->defaultItems(0)
                            ->addActionLabel('Add page section')
                            ->columnSpanFull(),
                    ]),
                Section::make('Reusable static blocks')
                    ->description('Link shared content that should appear on this page. The block is managed separately and can be reused elsewhere.')
                    ->schema([
                        Select::make('staticBlocks')
                            ->label('Linked static blocks')
                            ->relationship('staticBlocks', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Published blocks render after the page content.'),
                    ]),
                Section::make('Contact details')
                    ->description('Used when this page is the Contact page.')
                    ->schema([
                        TextInput::make('contact_email')->email(),
                        TextInput::make('contact_phone')->tel(),
                        Textarea::make('contact_address')->rows(3)->columnSpanFull(),
                        TextInput::make('map_url')->url()->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo_title'),
                        Textarea::make('seo_description')->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }
}

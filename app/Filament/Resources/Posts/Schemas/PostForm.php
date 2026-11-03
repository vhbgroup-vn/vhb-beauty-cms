<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post details')
                    ->description('Build journal articles, routine guides, ingredient explainers and brand stories.')
                    ->schema([
                        TextInput::make('title')->required()->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Optional. Generated from the post title when left blank.'),
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published', 'scheduled' => 'Scheduled'])
                            ->default('draft')
                            ->required(),
                        Select::make('category')
                            ->options([
                                'routine' => 'Routine & how-to',
                                'ingredients' => 'Ingredients',
                                'brand' => 'Brand stories',
                                'news' => 'News & launches',
                            ])
                            ->searchable(),
                        TextInput::make('author_name')->default('VHB Beauty'),
                        DateTimePicker::make('published_at')->label('Publish date'),
                        Toggle::make('featured')->label('Featured post'),
                        FileUpload::make('cover_image_path')
                            ->label('Cover image')
                            ->image()
                            ->disk('public')
                            ->directory('posts')
                            ->imageEditor()
                            ->maxSize(5120),
                    ])
                    ->columns(2),
                Section::make('Content')
                    ->schema([
                        Textarea::make('excerpt')->label('Excerpt')->rows(3)->columnSpanFull(),
                        RichEditor::make('content')->required()->columnSpanFull(),
                        TagsInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Add a tag and press Enter')
                            ->columnSpanFull(),
                    ]),
                Section::make('Reusable static blocks')
                    ->description('Link shared content that should appear after this post.')
                    ->schema([
                        Select::make('staticBlocks')
                            ->label('Linked static blocks')
                            ->relationship('staticBlocks', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Published blocks render after the post content.'),
                    ]),
                Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        TextInput::make('seo_title'),
                        Textarea::make('seo_description')->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use App\Models\ProductAttribute;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product basics')
                    ->description('The information customers see first.')
                    ->schema([
                        TextInput::make('name')->label('Product name')->required()->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Optional. Generated from the product name when left blank.'),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->unique(ignoreRecord: true)
                            ->helperText('Internal product code, e.g. VHB-SERUM-001'),
                        TextInput::make('brand')->default('VHB Beauty'),
                        Select::make('category')
                            ->options([
                                'skincare' => 'Skincare',
                                'haircare' => 'Haircare',
                                'body-care' => 'Body care',
                                'makeup' => 'Makeup',
                                'accessories' => 'Accessories',
                            ])
                            ->searchable()
                            ->required(),
                        Select::make('product_type')
                            ->label('Product type')
                            ->options(['simple' => 'Simple product', 'variable' => 'Variable product'])
                            ->default('simple')
                            ->live()
                            ->required(),
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published'])
                            ->default('draft')
                            ->required(),
                        Toggle::make('featured')->label('Featured product')->helperText('Show this product on the Home page.'),
                        Toggle::make('reviews_enabled')->label('Enable reviews')->default(true),
                    ])
                    ->columns(2),
                Section::make('Pricing & inventory')
                    ->description('WooCommerce-style pricing, stock and sale controls.')
                    ->schema([
                        Select::make('currency')
                            ->label('Display currency')
                            ->options(Product::currencyOptions())
                            ->default('VND')
                            ->required(),
                        TextInput::make('price')
                            ->label('Regular price')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Optional. Leave blank when the product requires a quote.'),
                        TextInput::make('price_min')
                            ->label('Price range — from')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Optional lower bound for a range or variable product.'),
                        TextInput::make('price_max')
                            ->label('Price range — to')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Optional upper bound for a range or variable product.'),
                        TextInput::make('sale_price')
                            ->label('Sale price')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Uses the selected display currency.'),
                        TextInput::make('compare_at_price')
                            ->label('Compare-at price')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Uses the selected display currency.'),
                        Select::make('stock_status')
                            ->label('Stock status')
                            ->options([
                                'in_stock' => 'In stock',
                                'out_of_stock' => 'Out of stock',
                                'on_backorder' => 'On backorder',
                            ])
                            ->default('in_stock')
                            ->required(),
                        Toggle::make('manage_stock')->label('Manage stock')->default(true)->live(),
                        TextInput::make('stock_quantity')
                            ->label('Stock quantity')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn (Get $get): bool => (bool) $get('manage_stock')),
                        Select::make('backorders')
                            ->label('Backorders')
                            ->options(['no' => 'Do not allow', 'notify' => 'Allow and notify customer', 'yes' => 'Allow'])
                            ->default('no'),
                        Toggle::make('sold_individually')->label('Limit to one per order'),
                        DateTimePicker::make('sale_starts_at')->label('Sale starts'),
                        DateTimePicker::make('sale_ends_at')->label('Sale ends'),
                    ])
                    ->columns(2),
                Section::make('Product media')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Main product image')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->imageEditor()
                            ->maxSize(5120),
                        FileUpload::make('gallery')
                            ->label('Product gallery')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->disk('public')
                            ->directory('products/gallery')
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Product content')
                    ->schema([
                        Textarea::make('short_description')->required()->rows(3)->columnSpanFull(),
                        RichEditor::make('description')->label('Full description')->columnSpanFull(),
                        TagsInput::make('benefits')
                            ->label('Key benefits')
                            ->placeholder('Add a benefit and press Enter')
                            ->columnSpanFull(),
                        TagsInput::make('tags')
                            ->label('Product tags')
                            ->placeholder('Add a tag and press Enter')
                            ->helperText('Useful for search, filters and merchandising.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Attributes')
                    ->description('Add structured product facts such as skin type, finish, shade, scent, volume or key ingredients.')
                    ->schema([
                        Repeater::make('attributes')
                            ->label('Product attributes')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Attribute name')
                                    ->required()
                                    ->placeholder('Skin type')
                                    ->datalist(fn (): array => ProductAttribute::query()
                                        ->where('active', true)
                                        ->orderBy('sort_order')
                                        ->orderBy('name')
                                        ->pluck('name')
                                        ->all()),
                                TagsInput::make('values')
                                    ->label('Values')
                                    ->required()
                                    ->placeholder('Sensitive, Normal')
                                    ->suggestions(fn (Get $get): array => ProductAttribute::query()
                                        ->where('active', true)
                                        ->where('name', $get('name'))
                                        ->first()?->values ?? [])
                                    ->helperText('Suggestions come from Catalog → Product attributes.'),
                                Toggle::make('visible')->label('Visible on product page')->default(true),
                                Toggle::make('used_for_variations')->label('Used for variations')->default(false),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->defaultItems(1)
                            ->addActionLabel('Add attribute')
                            ->columnSpanFull(),
                    ]),
                Section::make('Reusable static blocks')
                    ->description('Link shared content such as ingredient notes, shipping notices, routine guidance or campaign banners.')
                    ->schema([
                        Select::make('staticBlocks')
                            ->label('Linked static blocks')
                            ->relationship('staticBlocks', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Published blocks render after the product description.'),
                    ]),
                Section::make('Variations')
                    ->description('For variable products, define each sellable combination with its own price, image and stock.')
                    ->visible(fn (Get $get): bool => $get('product_type') === 'variable')
                    ->schema([
                        Repeater::make('variations')
                            ->label('Product variations')
                            ->schema([
                                TextInput::make('sku')->label('Variation SKU')->required(),
                                KeyValue::make('attributes')->label('Attribute combination')->keyLabel('Attribute')->valueLabel('Value'),
                                TextInput::make('price')->label('Regular price')->numeric()->minValue(0),
                                TextInput::make('sale_price')->label('Sale price')->numeric()->minValue(0),
                                Select::make('stock_status')
                                    ->label('Stock status')
                                    ->options(['in_stock' => 'In stock', 'out_of_stock' => 'Out of stock', 'on_backorder' => 'On backorder'])
                                    ->default('in_stock'),
                                TextInput::make('stock_quantity')->label('Stock quantity')->numeric()->minValue(0),
                                FileUpload::make('image_path')
                                    ->label('Variation image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products/variations')
                                    ->maxSize(5120),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['sku'] ?? null)
                            ->defaultItems(1)
                            ->addActionLabel('Add variation')
                            ->columnSpanFull(),
                    ]),
                Section::make('Shipping & purchase notes')
                    ->collapsed()
                    ->schema([
                        TextInput::make('weight')->numeric()->suffix('kg'),
                        TextInput::make('length')->numeric()->suffix('cm'),
                        TextInput::make('width')->numeric()->suffix('cm'),
                        TextInput::make('height')->numeric()->suffix('cm'),
                        TextInput::make('shipping_class')->label('Shipping class'),
                        Textarea::make('purchase_note')->label('Purchase note')->rows(3)->columnSpanFull(),
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

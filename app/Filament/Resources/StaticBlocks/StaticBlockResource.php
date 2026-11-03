<?php

namespace App\Filament\Resources\StaticBlocks;

use App\Filament\Resources\StaticBlocks\Pages\CreateStaticBlock;
use App\Filament\Resources\StaticBlocks\Pages\EditStaticBlock;
use App\Filament\Resources\StaticBlocks\Pages\ListStaticBlocks;
use App\Filament\Resources\StaticBlocks\Schemas\StaticBlockForm;
use App\Filament\Resources\StaticBlocks\Tables\StaticBlocksTable;
use App\Models\StaticBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StaticBlockResource extends Resource
{
    protected static ?string $model = StaticBlock::class;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Static blocks';

    protected static ?string $modelLabel = 'Static block';

    protected static ?string $pluralModelLabel = 'Static blocks';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StaticBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaticBlocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaticBlocks::route('/'),
            'create' => CreateStaticBlock::route('/create'),
            'edit' => EditStaticBlock::route('/{record}/edit'),
        ];
    }
}

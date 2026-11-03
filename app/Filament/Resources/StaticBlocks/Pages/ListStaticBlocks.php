<?php

namespace App\Filament\Resources\StaticBlocks\Pages;

use App\Filament\Resources\StaticBlocks\StaticBlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaticBlocks extends ListRecords
{
    protected static string $resource = StaticBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

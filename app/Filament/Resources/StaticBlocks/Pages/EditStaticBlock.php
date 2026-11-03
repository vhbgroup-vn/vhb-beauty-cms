<?php

namespace App\Filament\Resources\StaticBlocks\Pages;

use App\Filament\Resources\StaticBlocks\StaticBlockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStaticBlock extends EditRecord
{
    protected static string $resource = StaticBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Products', Product::query()->count())
                ->description(Product::query()->where('status', 'published')->count().' published')
                ->color('success'),
            Stat::make('Low stock', Product::query()->where('manage_stock', true)->where('stock_quantity', '<=', 5)->count())
                ->description('Products needing attention')
                ->color('warning'),
            Stat::make('Posts', Post::query()->count())
                ->description(Post::query()->where('status', 'published')->count().' published')
                ->color('info'),
            Stat::make('Pages', Page::query()->where('published', true)->count())
                ->description('Published site pages')
                ->color('primary'),
        ];
    }
}

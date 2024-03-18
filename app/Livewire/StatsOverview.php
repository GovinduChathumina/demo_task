<?php
 
namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
 
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tasks = Task::get();
        return [

            Stat::make('Total Tasks', $tasks->count()),
        ];
    }
}
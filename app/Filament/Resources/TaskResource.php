<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Widgets\Widget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Card::make()->schema([
                    TextInput::make('title')->required(),
                    RichEditor::make('description')->required(),
                    Select::make('status')
                        ->options([
                            'Open' => 'Open',
                            'Pending' => 'Pending',
                            'In-progress' => 'In-progress',
                            'In-review' => 'In-review',
                            'Accepted' => 'Accepted',
                            'Rejected' => 'Rejected',
                        ]),
                    DatePicker::make('due_date')->required()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('title')->limit(20)->sortable()->searchable(),
                TextColumn::make('description')->limit(50)->sortable()->searchable(),
                TextColumn::make('status')->limit(50)->sortable()->searchable(),
                TextColumn::make('due_date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'Open' => 'Open',
                    'Pending' => 'Pending',
                    'In-progress' => 'In-progress',
                    'In-review' => 'In-review',
                    'Accepted' => 'Accepted',
                    'Rejected' => 'Rejected',
                ])
                ->label('Status')
                ->placeholder('Select status')
                ->attribute('status')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function widget(Widget $widget): Widget
    {
        return $widget
            ->title('Stats Overview')
            ->view('filament.widgets.stats_overview', [
                'totalTasks' => Task::count(),
                'openTasks' => Task::where('status', 'Open')->count(),
                'pendingTasks' => Task::where('status', 'Pending')->count(),
                'inProgressTasks' => Task::where('status', 'In-progress')->count(),
                'inReviewTasks' => Task::where('status', 'In-review')->count(),
                'acceptedTasks' => Task::where('status', 'Accepted')->count(),
                'rejectedTasks' => Task::where('status', 'Rejected')->count(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}

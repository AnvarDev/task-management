<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
                Select::make('project_id')->label('Project')
                    ->searchable()
                    ->getOptionLabelUsing(fn($value) => Project::find($value)?->title)
                    ->getSearchResultsUsing(fn(string $query) =>
                    Project::where('title', 'like', "%{$query}%")->orWhere('id', $query)->limit(20)->pluck('title', 'id')),
                Select::make('user_id')->label('Assignee')
                    ->searchable()
                    ->getOptionLabelUsing(fn($value) => User::find($value)?->name)
                    ->getSearchResultsUsing(fn(string $query) =>
                    User::where('name', 'like', "%{$query}%")->orWhere('email', "%{$query}%")->limit(20)->pluck('name', 'id')),
                DateTimePicker::make('date')->label('Due date')
                    ->minDate(now()),
                Textarea::make('description'),
                FileUpload::make('attachment')
                    ->deletable()
                    ->previewable(false),
                Radio::make('priority')
                    ->options(config('tasks.priority'))
                    ->required(),
                Radio::make('status')
                    ->options(config('tasks.status'))
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where('title', 'like', "%$search%");
                }),
                TextColumn::make('date')->label('Due date'),
                TextColumn::make('project_id')->label('Project')->formatStateUsing(fn(string $state): string => Project::find($state)?->title),
                TextColumn::make('priority')->formatStateUsing(fn(string $state): string => config('tasks.priority')[$state]),
                TextColumn::make('status')->formatStateUsing(fn(string $state): string => config('tasks.status')[$state]),
                TextColumn::make('created_at'),
            ])
            ->filters([
                SelectFilter::make('project_id')->label('Project')
                    ->multiple()
                    ->searchable()
                    ->getOptionLabelsUsing(fn(array $values) => Project::whereIn('id', $values)->pluck('title', 'id'))
                    ->getSearchResultsUsing(fn(string $query) =>
                    Project::where('title', 'like', "%{$query}%")->orWhere('id', $query)->limit(20)->pluck('title', 'id')),
                SelectFilter::make('priority')
                    ->multiple()
                    ->options(config('tasks.priority')),
                SelectFilter::make('status')
                    ->multiple()
                    ->options(config('tasks.status')),
                Filter::make('date')
                    ->form([
                        DateTimePicker::make('date_from')->label('Due date from'),
                        DateTimePicker::make('date_until')->label('Due date to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    // TODO remove the function and use the booted or delete Model methods to delete all related models
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return $record->comments()->count() ? false : true;
    }
}

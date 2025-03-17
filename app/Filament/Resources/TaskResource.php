<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Project;
use App\Models\Task;
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
use Filament\Tables\Table;

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
                    ->getOptionLabelUsing(fn($value): ?string => Project::find($value)?->title)
                    ->getSearchResultsUsing(fn(string $query) =>
                    Project::where('title', 'like', "%{$query}%")->orWhere('id', $query)->limit(20)->pluck('title', 'id')),
                Textarea::make('description'),
                DateTimePicker::make('date')
                    ->minDate(now()),
                FileUpload::make('attachment'),
                Radio::make('priority')
                    ->options(config('tasks.priority'))
                    ->required(),
                Radio::make('status')
                    ->options(config('tasks.status'))
                    ->required(),
                Hidden::make('user_id')->default(auth()->user()->getKey()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('date')->label('Due date'),
                TextColumn::make('project_id')->label('Project')->formatStateUsing(fn(string $state): string => Project::find($state)?->title),
                TextColumn::make('priority')->formatStateUsing(fn(string $state): string => config('tasks.priority')[$state]),
                TextColumn::make('status')->formatStateUsing(fn(string $state): string => config('tasks.status')[$state]),
                TextColumn::make('date')->label('Due date'),
                TextColumn::make('created_at'),
            ])
            ->filters([
                //
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
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->unique(table: User::class)
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->filled()
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->filled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");
                }),
                TextColumn::make('email'),
                TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // TODO remove the function and use the booted or delete Model methods to delete all related models
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return $record->getKey() === auth()->user()->getKey() ||
            $record->tasks()->count() ||
            $record->comments()->count() ? false : true;
    }
}

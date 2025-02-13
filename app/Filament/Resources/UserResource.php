<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->required(),
                Forms\Components\TextInput::make('score'),
                Forms\Components\TextInput::make('latest_step'),
                Forms\Components\TextInput::make('password')->required(),
                Forms\Components\TextInput::make('password_confirmation')->required()->same('password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('score'),
                Tables\Columns\TextColumn::make('latest_step'),
            ])
            ->filters([
                Tables\Filters\Filter::make('score')
                ->form([
                    Forms\Components\TextInput::make('score')->label('Score'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (isset($data['score'])) {
                        $query->where('score', '=', $data['score']);
                    }
                }),

                Tables\Filters\Filter::make('latest_step')
                    ->form([
                        Forms\Components\TextInput::make('latest_step')->label('Latest Step'),
                    ])
                ->query(function (Builder $query, array $data) {
                    if (isset($data['latest_step'])) {
                        $query->where('latest_step', '=', $data['latest_step']);
                    }
                }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

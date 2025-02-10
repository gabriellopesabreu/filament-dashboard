<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('brand')
                    ->required()
                    ->label('Marca'),
                TextInput::make('model')
                    ->required()
                    ->label('Modelo'),
                TextInput::make('year')
                    ->required()
                    ->label('Ano'),
                TextInput::make('plate')
                    ->required()
                    ->label('Placa'),
                TextInput::make('color')
                    ->required()
                    ->label('Cor'),
                Textarea::make('observations')
                    ->label('Observações'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand')
                 ->label('Marca')
                 ->searchable()
                 ->sortable(),
                TextColumn::make('model')
                 ->label('Modelo')
                 ->searchable()
                 ->sortable(),

                TextColumn::make('year')
                 ->label('Ano')
                 ->searchable(),

                TextColumn::make('plate')
                 ->label('Placa')
                 ->searchable(),

                TextColumn::make('color')
                 ->label('Cor')
                 ->searchable(),

                TextColumn::make('observations')
                 ->label('Observações'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}

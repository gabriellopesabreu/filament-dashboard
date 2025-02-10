<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\TextColumn;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome'),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Sobrenome'),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->label('Telefone'),
                Forms\Components\TextInput::make('cpf')
                    ->unique()
                    ->required()
                    ->label('CPF'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique()
                    ->label('Email'),
                Forms\Components\TextInput::make('zip_code')
                    ->label('CEP'),
                Forms\Components\TextInput::make('country')
                    ->label('País'),
                Forms\Components\TextInput::make('state')
                    ->label('Estado'),
                Forms\Components\TextInput::make('city')
                    ->label('Cidade'),
                Forms\Components\TextInput::make('neighborhood')
                    ->label('Bairro'),
                Forms\Components\Textarea::make('address')
                    ->label('Endereço'),
            ]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('last_name')
                    ->searchable()
                    ->label('Sobrenome'),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Telefone'),
                TextColumn::make('cpf')
                    ->searchable()
                    ->label('CPF'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('zip_code')
                    ->searchable()
                    ->label('CEP'),
                TextColumn::make('country')
                    ->searchable()
                    ->label('País'),
                TextColumn::make('state')
                    ->searchable()
                    ->label('Estado'),
                TextColumn::make('city')
                    ->searchable()
                    ->label('Cidade'),
                TextColumn::make('neighborhood')
                    ->searchable()
                    ->label('Bairro'),
                TextColumn::make('address')
                    ->searchable()
                    ->label('Endereço'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}

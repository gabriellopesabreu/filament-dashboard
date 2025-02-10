<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Part;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'vehicle']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->label('Cliente'),
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'model')
                    ->required()
                    ->label('Veículo'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Aberto' => 'Pendente',
                        'Em andamento' => 'Em Progresso',
                        'Concluído' => 'Concluído',
                    ])
                    ->required()
                    ->label('Status'),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->required(),
                //
                Forms\Components\Repeater::make('part_id')
                    ->schema([
                        Forms\Components\Select::make('part_id')
                            ->relationship('parts', 'name')
                            ->label('Peça'),

                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->rule(fn ($get) => 'lte:' . (\App\Models\Part::find($get('part_id'))?->quantity ?? 1)) // Valida que quantity <= estoque
                            ->label('Quantidade')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                $quantity = $get('quantity') ?? 1,
                                $set('quantity', $quantity),
                                // Atualiza subtotal ao alterar quantidade
                                $set('subtotal', ($get('unit_price') ?? 0) * $quantity);
                            ),
                            // ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            //     $price = \App\Models\Part::find($state)?->price ?? 0,
                            //     $set('unit_price', $price),
                            //     // Atualiza subtotal ao selecionar peça
                            //     $set('subtotal', $price * ($get('quantity') ?? 1));
                            // ),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled(), // Apenas visualização automática
                    ])
                    ->label('Peças Utilizadas'),
                    //->columnSpanFull()
                    
                Forms\Components\TextInput::make('extra_value')
                    ->label('Valor do Seviço')
                    ->numeric()
                    ->reactive()
                    ->default(0),

                Forms\Components\TextInput::make('valor_total')
                    ->numeric()
                    ->disabled()
                    ->label('Valor Total')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, $get) => 
                        $set('valor_total', collect($get('parts'))
                            ->sum(fn ($item) => $item['subtotal'] ?? 0) + ($get('extra_value') ?? 0))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Cliente'),

                TextColumn::make('vehicle.model')
                    ->label('Veículo')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->icon(fn (string $state): string => match ($state) {
                        'Aberto' => 'heroicon-o-pencil',
                        'Em andamento' => 'heroicon-o-clock',
                        'Concluído' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Aberto' => 'info',
                        'Em andamento' => 'warning',
                        'Concluído' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
                TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->searchable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

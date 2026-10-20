<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Kamar';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('boarding_house_id')
                    ->label('Kos Kosan')
                    ->relationship('boardingHouse', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('room_type')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('square_feet')
                    ->numeric()
                    ->required()
                    ->suffix('m\u00b2'),
                Forms\Components\TextInput::make('capacity')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('price_per_month')
                    ->numeric()
                    ->prefix('Rp ')
                    ->required(),
                Forms\Components\Toggle::make('is_available')
                    ->label('Tersedia')
                    ->required(),
                Forms\Components\Repeater::make('images')
                    ->relationship('roomImages')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required()
                            ->directory('boarding-houses-rooms')
                            ->imagePreviewHeight('150'),
                    ])
                    ->columnSpan(2),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('boardingHouse.name')
                    ->label('Kos Kosan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('square_feet')
                    ->suffix('m\u00b2'),
                Tables\Columns\TextColumn::make('capacity'),
                Tables\Columns\TextColumn::make('price_per_month')
                    ->money('idr'),
                Tables\Columns\BadgeColumn::make('is_available')
                    ->label('Tersedia')
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoardingHouseResource\Pages;
use App\Models\BoardingHouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BoardingHouseResource extends Resource
{
    protected static ?string $model = BoardingHouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Kos Kosan';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Master Data';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Umum')
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->required()
                                    ->directory('boarding-houses'),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(100)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', str()->slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\Select::make('city_id')
                                    ->relationship('city', 'name')
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                Forms\Components\RichEditor::make('description')
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->maxLength(100)
                                    ->prefix('Rp ')
                                    ->required(),
                                Forms\Components\TextArea::make('address')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Bonus & Fasilitas')
                            ->schema([
                                Forms\Components\Repeater::make('bonuses')
                                    ->relationship('bonuses')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->required()
                                            ->directory('boarding-houses-bonuses'),
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\RichEditor::make('description')
                                            ->required(),
                                    ])
                            ]),
                        Forms\Components\Tabs\Tab::make('Kamar')
                            ->schema([
                                Forms\Components\Repeater::make('rooms')
                                    ->relationship('rooms')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('room_type')
                                            ->required(),
                                        Forms\Components\TextInput::make('square_feet')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('capacity')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('price_per_month')
                                            ->numeric()
                                            ->prefix('Rp ')
                                            ->required(),
                                        Forms\Components\Toggle::make('is_available')
                                            ->required(),
                                        Forms\Components\Repeater::make('images')
                                            ->relationship('roomImages')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->image()
                                                    ->required()
                                                    ->directory('boarding-houses-rooms'),
                                            ])
                                    ])
                            ])
                    ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('price'),
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
            'index' => Pages\ListBoardingHouses::route('/'),
            'create' => Pages\CreateBoardingHouse::route('/create'),
            'edit' => Pages\EditBoardingHouse::route('/{record}/edit'),
        ];
    }
}

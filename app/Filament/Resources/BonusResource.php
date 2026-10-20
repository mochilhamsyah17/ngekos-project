<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BonusResource\Pages;
use App\Models\Bonus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BonusResource extends Resource
{
    protected static ?string $model = Bonus::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Bonus & Fasilitas';

    protected static ?int $navigationSort = 5;

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
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required()
                    ->directory('boarding-houses-bonuses')
                    ->imagePreviewHeight('200')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100)
                    ->columnSpan(2),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('boardingHouse.name')
                    ->label('Kos Kosan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListBonuses::route('/'),
            'create' => Pages\CreateBonus::route('/create'),
            'edit' => Pages\EditBonus::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\ItemStatus;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\Pages\EditItem;
use App\Filament\Resources\ItemResource\Pages\ListItems;
use App\Filament\Resources\ItemResource\Pages\CreateItem;
use App\Filament\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::columnTableSchema())
            ->filters([
                SelectFilter::make('status')
                    ->optionsLimit(5)
                    ->options(ItemStatus::class)
                    ->preload()
                    ->searchable(),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                ViewAction::make()
                    ->modalWidth('lg')
                    ->slideOver(),
                ActionGroup::make([
                    Action::make('edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->url(fn(Item $record) => self::getUrl('edit', ['record' => $record])),
                    DeleteAction::make()
                        ->requiresConfirmation(),
                ])
                    ->tooltip('More actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function columnTableSchema()
    {
        return [
            TextColumn::make('submited_by.name')
                ->searchable()
                ->sortable()
                ->label('Submited By'),
            TextColumn::make('title')
                ->searchable()
                ->sortable(),
            TextColumn::make('type')->sortable()->searchable(),
            TextColumn::make('status')->sortable()->searchable(),
            SelectColumn::make('verified')
                ->options([
                    true => 'verified',
                    false => 'not verified'
                ]),
            TextColumn::make('location')
        ];
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}

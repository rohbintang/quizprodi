<?php

namespace App\Filament\Admin\Resources\Submissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->limit(25),
                TextColumn::make('whatsapp')
                    ->searchable()
                    ->limit(18),
                TextColumn::make('asal_sekolah')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('kota')
                    ->searchable()
                    ->limit(15)
                    ->toggleable(),
                IconColumn::make('izin_dihubungi')
                    ->label('Izin Hubungi')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('usia')
                    ->label('Usia')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('skor_sainsdata')
                    ->label('Sains Data')
                    ->numeric(1)
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('skor_ai_robotika')
                    ->label('AI & Robotika')
                    ->numeric(1)
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('skor_keamanan')
                    ->label('Keamanan Siber')
                    ->numeric(1)
                    ->sortable()
                    ->badge()
                    ->color('danger'),
                TextColumn::make('rekomendasi')
                    ->label('Rekomendasi')
                    ->searchable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('rekomendasi')
                    ->label('Rekomendasi')
                    ->options([
                        'Sains Data Terapan' => 'Sains Data Terapan',
                        'AI & Robotika' => 'AI & Robotika',
                        'Rekayasa Keamanan Siber' => 'Rekayasa Keamanan Siber',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

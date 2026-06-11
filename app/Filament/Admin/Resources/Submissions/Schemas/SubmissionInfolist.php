<?php

namespace App\Filament\Admin\Resources\Submissions\Schemas;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Schemas\Schema;

class SubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Diri')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('nama')->label('Nama'),
                            TextEntry::make('email')->label('Email'),
                            TextEntry::make('whatsapp')->label('WhatsApp'),
                            TextEntry::make('asal_sekolah')->label('Asal Sekolah')->placeholder('-'),
                            TextEntry::make('kota')->label('Kota')->placeholder('-'),
                            TextEntry::make('usia')->label('Usia')->placeholder('-'),
                        ]),
                        IconEntry::make('izin_dihubungi')->label('Izin Dihubungi')->boolean(),
                        TextEntry::make('minat_lain')->label('Minat Lain')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Jawaban Kuis')
                    ->schema([
                        Grid::make(5)->schema(
                            collect(range(1, 15))->map(fn ($i) =>
                                TextEntry::make("q{$i}")
                                    ->label("Q{$i}")
                                    ->badge()
                                    ->color(fn ($state) => $state >= 4 ? 'success' : ($state <= 2 ? 'danger' : 'warning'))
                            )->toArray()
                        ),
                    ]),

                Section::make('Hasil')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('skor_sainsdata')->label('Sains Data')->numeric(2)->badge()->color('info'),
                            TextEntry::make('skor_ai_robotika')->label('AI & Robotika')->numeric(2)->badge()->color('warning'),
                            TextEntry::make('skor_keamanan')->label('Keamanan Siber')->numeric(2)->badge()->color('danger'),
                        ]),
                        TextEntry::make('rekomendasi')->label('Rekomendasi')->badge()->color('success')->size('lg'),
                    ]),

                Section::make('Timestamp')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')->label('Dibuat')->dateTime(),
                            TextEntry::make('updated_at')->label('Diubah')->dateTime(),
                        ]),
                    ]),
            ]);
    }
}

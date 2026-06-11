<?php

namespace App\Filament\Admin\Resources\Submissions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Diri')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama')->required(),
                            TextInput::make('email')->email()->required(),
                            TextInput::make('whatsapp')->required()->label('WhatsApp'),
                            TextInput::make('asal_sekolah')->label('Asal Sekolah'),
                            TextInput::make('kota')->label('Kota'),
                            TextInput::make('usia')->numeric()->minValue(12)->maxValue(60)->label('Usia'),
                        ]),
                        Toggle::make('izin_dihubungi')->label('Izin Dihubungi')->default(true),
                        Textarea::make('minat_lain')->label('Minat Lain')->columnSpanFull(),
                    ]),

                Section::make('Jawaban (1-5)')
                    ->schema([
                        Grid::make(5)->schema(
                            collect(range(1, 15))->map(fn ($i) =>
                                TextInput::make("q{$i}")
                                    ->label("Q{$i}")
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->default(3)
                                    ->required()
                            )->toArray()
                        ),
                    ]),

                Section::make('Skor & Rekomendasi')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('skor_sainsdata')->label('Skor Sains Data')->numeric()->disabled(),
                            TextInput::make('skor_ai_robotika')->label('Skor AI & Robotika')->numeric()->disabled(),
                            TextInput::make('skor_keamanan')->label('Skor Keamanan Siber')->numeric()->disabled(),
                        ]),
                        TextInput::make('rekomendasi')->label('Rekomendasi')->disabled(),
                    ]),
            ]);
    }
}

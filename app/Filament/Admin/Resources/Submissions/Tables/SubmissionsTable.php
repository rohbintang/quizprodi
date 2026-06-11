<?php

namespace App\Filament\Admin\Resources\Submissions\Tables;

use App\Models\Submission;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
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
                    ->color('success')
                    ->limit(20),
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
            ->headerActions([
                self::exportAllCsvAction(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected static function exportAllCsvAction(): Action
    {
        return Action::make('exportAllCsv')
            ->label('Export CSV')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function (): StreamedResponse {
                return self::generateCsv(Submission::orderBy('created_at', 'desc')->get());
            });
    }

    protected static function getCsvFilename(): string
    {
        return 'submissions-' . now()->format('Y-m-d-His') . '.csv';
    }

    protected static function generateCsv($records): StreamedResponse
    {
        $filename = self::getCsvFilename();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($handle, [
                '#', 'Nama', 'Email', 'WhatsApp', 'Asal Sekolah', 'Kota', 'Usia',
                'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9', 'Q10',
                'Q11', 'Q12', 'Q13', 'Q14', 'Q15',
                'Skor Sains Data', 'Skor AI Robotika', 'Skor Keamanan Siber',
                'Rekomendasi', 'Tanggal',
            ]);

            // Data rows
            foreach ($records as $i => $record) {
                fputcsv($handle, [
                    $i + 1,
                    $record->nama,
                    $record->email,
                    $record->whatsapp,
                    $record->asal_sekolah ?? '',
                    $record->kota ?? '',
                    $record->usia ?? '',
                    $record->q1 ?? '', $record->q2 ?? '', $record->q3 ?? '',
                    $record->q4 ?? '', $record->q5 ?? '', $record->q6 ?? '',
                    $record->q7 ?? '', $record->q8 ?? '', $record->q9 ?? '',
                    $record->q10 ?? '', $record->q11 ?? '', $record->q12 ?? '',
                    $record->q13 ?? '', $record->q14 ?? '', $record->q15 ?? '',
                    $record->skor_sainsdata ?? '',
                    $record->skor_ai_robotika ?? '',
                    $record->skor_keamanan ?? '',
                    $record->rekomendasi ?? '',
                    $record->created_at?->format('d M Y, H:i') ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}

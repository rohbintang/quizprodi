<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Submission;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubmissionStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Minat Meter';

    protected function getStats(): array
    {
        $total = Submission::count();

        $topRekom = Submission::select('rekomendasi')
            ->whereNotNull('rekomendasi')
            ->groupBy('rekomendasi')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $avgSainsdata = Submission::avg('skor_sainsdata') ?? 0;
        $avgAiRobotika = Submission::avg('skor_ai_robotika') ?? 0;
        $avgKeamanan = Submission::avg('skor_keamanan') ?? 0;

        $today = Submission::whereDate('created_at', today())->count();

        $topProdi = $topRekom
            ? $topRekom->rekomendasi
            : '-';

        $topCount = $topRekom
            ? Submission::where('rekomendasi', $topProdi)->count()
            : 0;

        return [
            Stat::make('Total Submissions', number_format($total))
                ->description('Semua data masuk')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Hari Ini', number_format($today))
                ->description('Submissions hari ini')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),

            Stat::make('Top Rekomendasi', $topProdi)
                ->description("{$topCount} dari {$total} responden")
                ->descriptionIcon('heroicon-o-trophy')
                ->color('warning'),

            Stat::make('Rata-rata Skor AI', number_format($avgAiRobotika, 1))
                ->description('Skor AI & Robotika tertinggi rata-rata')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }
}

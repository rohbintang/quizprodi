<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Submission;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SubmissionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Submissions per Hari (7 Hari Terakhir)';

    protected ?string $maxHeight = '250px';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $days->push(now()->subDays($i));
        }

        $labels = $days->map(fn ($d) => $d->format('d M'))->toArray();

        $sainsdata = [];
        $aiRobotika = [];
        $keamanan = [];

        foreach ($days as $day) {
            $dayStr = $day->format('Y-m-d');
            $sainsdata[] = Submission::whereDate('created_at', $day)
                ->where('rekomendasi', 'Sains Data Terapan')
                ->count();
            $aiRobotika[] = Submission::whereDate('created_at', $day)
                ->where('rekomendasi', 'AI & Robotika')
                ->count();
            $keamanan[] = Submission::whereDate('created_at', $day)
                ->where('rekomendasi', 'Rekayasa Keamanan Siber')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sains Data Terapan',
                    'data' => $sainsdata,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'AI & Robotika',
                    'data' => $aiRobotika,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.7)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Rekayasa Keamanan Siber',
                    'data' => $keamanan,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

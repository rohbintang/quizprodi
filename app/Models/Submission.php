<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'whatsapp',
        'asal_sekolah',
        'kota',
        'izin_dihubungi',
        'usia',
        'minat_lain',
        'q1', 'q2', 'q3', 'q4', 'q5',
        'q6', 'q7', 'q8', 'q9', 'q10',
        'q11', 'q12', 'q13', 'q14', 'q15',
        'skor_sainsdata',
        'skor_ai_robotika',
        'skor_keamanan',
        'rekomendasi',
    ];

    protected $casts = [
        'izin_dihubungi' => 'boolean',
        'skor_sainsdata' => 'decimal:2',
        'skor_ai_robotika' => 'decimal:2',
        'skor_keamanan' => 'decimal:2',
    ];

    /**
     * Calculate NORMALIZED scores (0-100 scale).
     * Each prodi score is divided by its max possible score × 100.
     * This ensures fairness across all 3 prodi regardless of question count.
     */
    public static function calculateScores(array $answers): array
    {
        $Q = $answers;

        // Raw scores
        $sainsdataRaw = $Q['q1'] + $Q['q5'] + 0.6 * $Q['q9'] + 0.7 * $Q['q10'] + 0.7 * $Q['q12'] + 0.8 * $Q['q14'];
        $aiRobotikaRaw = $Q['q2'] + $Q['q6'] + $Q['q3'] + $Q['q7'] + 0.4 * $Q['q10'] + 0.8 * $Q['q12'] + 0.8 * $Q['q14'] + 0.6 * $Q['q15'];
        $keamananRaw = $Q['q4'] + $Q['q8'] + 0.8 * $Q['q9'] + 0.7 * $Q['q13'];

        // Max possible (all answers = 5)
        $sainsdataMax = 5 + 5 + 0.6*5 + 0.7*5 + 0.7*5 + 0.8*5;
        $aiRobotikaMax = 5 + 5 + 5 + 5 + 0.4*5 + 0.8*5 + 0.8*5 + 0.6*5;
        $keamananMax = 5 + 5 + 0.8*5 + 0.7*5;

        // Normalize to 0-100
        $sainsdata = round(($sainsdataRaw / $sainsdataMax) * 100, 1);
        $aiRobotika = round(($aiRobotikaRaw / $aiRobotikaMax) * 100, 1);
        $keamanan = round(($keamananRaw / $keamananMax) * 100, 1);

        $scores = [
            'Sains Data Terapan' => $sainsdata,
            'AI & Robotika' => $aiRobotika,
            'Rekayasa Keamanan Siber' => $keamanan,
        ];

        $topScore = max($scores);
        $rekomendasi = implode(' & ', array_keys(array_filter($scores, fn ($v) => abs($v - $topScore) < 0.001)));

        return [
            'skor_sainsdata' => $sainsdata,
            'skor_ai_robotika' => $aiRobotika,
            'skor_keamanan' => $keamanan,
            'rekomendasi' => $rekomendasi,
            'scores_array' => $scores,
        ];
    }
}

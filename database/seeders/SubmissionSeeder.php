<?php

namespace Database\Seeders;

use App\Models\Submission;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            'SMA 1 Yogyakarta', 'SMA 2 Sleman', 'SMA Negeri 3 Bantul',
            'SMA Muhammadiyah 1', 'SMA Taruna Nusantara', 'SMK Negeri 1 Yogyakarta',
            'SMA 1 Depok Sleman', 'SMA 4 Yogyakarta', 'MA Negeri 1 Yogyakarta',
            'SMK Pariwisata', 'SMA 1 Kalasan', 'SMA 2 Bantul',
        ];

        $cities = ['Yogyakarta', 'Sleman', 'Bantul', 'Gunung Kidul', 'Kulon Progo', 'Solo'];

        $names = [
            'Ahmad Rizki', 'Budi Santoso', 'Citra Dewi', 'Dian Permata', 'Eka Putri',
            'Fajar Nugroho', 'Gita Puspita', 'Hendra Wijaya', 'Indah Sari', 'Joko Prasetyo',
            'Kartika Sari', 'Lukman Hakim', 'Maya Angelina', 'Nur Hidayat', 'Octaviana',
            'Putri Wulandari', 'Rizky Pratama', 'Siti Nurhaliza', 'Taufiqurrahman', 'Ulya Maghfiroh',
            'Vina Oktaviani', 'Wahyu Saputra', 'Xena Putri', 'Yoga Aditya', 'Zahra Amalia',
        ];

        $rekomendasi = ['Sains Data Terapan', 'AI & Robotika', 'Rekayasa Keamanan Siber'];

        $answers = [];
        for ($i = 1; $i <= 15; $i++) {
            $answers["q{$i}"] = fake()->numberBetween(1, 5);
        }

        foreach ($names as $i => $name) {
            $q = [];
            for ($j = 1; $j <= 15; $j++) {
                $q["q{$j}"] = fake()->numberBetween(1, 5);
            }

            $sainsdata = $q['q1'] + $q['q5'] + 0.6 * $q['q9'] + 0.7 * $q['q10'] + 0.7 * $q['q12'] + 0.8 * $q['q14'];
            $aiRobotika = $q['q2'] + $q['q6'] + $q['q3'] + $q['q7'] + 0.4 * $q['q10'] + 0.8 * $q['q12'] + 0.8 * $q['q14'] + 0.6 * $q['q15'];
            $keamanan = $q['q4'] + $q['q8'] + 0.8 * $q['q9'] + 0.7 * $q['q13'];

            $scores = [
                'Sains Data Terapan' => round($sainsdata, 2),
                'AI & Robotika' => round($aiRobotika, 2),
                'Rekayasa Keamanan Siber' => round($keamanan, 2),
            ];
            $topScore = max($scores);
            $rekom = implode(' & ', array_keys(array_filter($scores, fn ($v) => abs($v - $topScore) < 0.001)));

            $daysAgo = fake()->numberBetween(0, 6);
            $createdAt = now()->subDays($daysAgo)->addHours(fake()->numberBetween(8, 17))->addMinutes(fake()->numberBetween(0, 59));

            Submission::create([
                'nama' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@' . fake()->safeEmailDomain(),
                'whatsapp' => '+62' . fake()->numerify('812#########'),
                'asal_sekolah' => fake()->randomElement($schools),
                'kota' => fake()->randomElement($cities),
                'usia' => fake()->numberBetween(15, 20),
                'izin_dihubungi' => fake()->boolean(85),
                'minat_lain' => fake()->optional(0.6)->sentence(5),
                ...$q,
                'skor_sainsdata' => round($sainsdata, 2),
                'skor_ai_robotika' => round($aiRobotika, 2),
                'skor_keamanan' => round($keamanan, 2),
                'rekomendasi' => $rekom,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}

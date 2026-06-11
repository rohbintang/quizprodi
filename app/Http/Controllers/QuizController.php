<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function index()
    {
        return view('quiz.index');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:120',
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^0\d{8,13}$/'],
            'asal_sekolah' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'usia' => 'nullable|integer|min:12|max:60',
            'minat_lain' => 'nullable|string|max:1000',
            'izin_dihubungi' => 'nullable|boolean',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Format WhatsApp harus 08xxxxxxxxxx.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate answers
        $answers = [];
        for ($i = 1; $i <= 15; $i++) {
            $val = (int) $request->input("q{$i}", 3);
            $answers["q{$i}"] = max(1, min(5, $val));
        }

        // Calculate NORMALIZED scores (0-100 scale)
        // Each prodi score is divided by its max possible score, then × 100
        $Q = $answers;

        // Raw scores (same formula as original)
        $sainsdataRaw = $Q['q1'] + $Q['q5'] + 0.6 * $Q['q9'] + 0.7 * $Q['q10'] + 0.7 * $Q['q12'] + 0.8 * $Q['q14'];
        $aiRobotikaRaw = $Q['q2'] + $Q['q6'] + $Q['q3'] + $Q['q7'] + 0.4 * $Q['q10'] + 0.8 * $Q['q12'] + 0.8 * $Q['q14'] + 0.6 * $Q['q15'];
        $keamananRaw = $Q['q4'] + $Q['q8'] + 0.8 * $Q['q9'] + 0.7 * $Q['q13'];

        // Max possible scores (when all answers = 5)
        $sainsdataMax = 5 + 5 + 0.6*5 + 0.7*5 + 0.7*5 + 0.8*5;     // 24.0
        $aiRobotikaMax = 5 + 5 + 5 + 5 + 0.4*5 + 0.8*5 + 0.8*5 + 0.6*5; // 33.0
        $keamananMax = 5 + 5 + 0.8*5 + 0.7*5;                         // 17.5

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

        $submission = Submission::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'asal_sekolah' => $request->asal_sekolah,
            'kota' => $request->kota,
            'usia' => $request->usia,
            'minat_lain' => $request->minat_lain,
            'izin_dihubungi' => $request->boolean('izin_dihubungi', true),
            ...$answers,
            'skor_sainsdata' => $sainsdata,
            'skor_ai_robotika' => $aiRobotika,
            'skor_keamanan' => $keamanan,
            'rekomendasi' => $rekomendasi,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'scores' => $scores,
                'rekomendasi' => $rekomendasi,
                'nama' => $submission->nama,
                'email' => $submission->email,
                'whatsapp' => $submission->whatsapp,
                'asal_sekolah' => $submission->asal_sekolah,
                'kota' => $submission->kota,
                'izin_dihubungi' => $submission->izin_dihubungi,
            ],
        ]);
    }
}

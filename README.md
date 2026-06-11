# Minat Meter - PLAI BMD

🎯 **Tes Minat Interaktif** untuk Politeknik Artificial Intelligence Budi Mulia Dua (PLAI BMD) — Kampus AI pertama di Indonesia.

## Fitur

- **Quiz Interaktif** — 15 pertanyaan 1-at-a-time dengan animasi (Alpine.js)
- **3 Prodi** — Sains Data Terapan, AI & Robotika, Rekayasa Keamanan Siber
- **Scoring Normalisasi** — 0-100%, fair untuk semua prodi
- **Export IG Card** — 1080x1080px, dark theme + gold, download via html2canvas
- **Admin Panel** — Filament 5: dashboard, submissions CRUD, chart, stats
- **Export CSV** — Download semua data submissions ke CSV

## Tech Stack

- Laravel 12 + Filament 5
- MySQL
- Alpine.js (SPA-like quiz)
- html2canvas (IG image export)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed   # Admin user + sample submissions
php artisan serve
```

Admin: `http://localhost:8000/admin`
Login: `admin@minatmeter.com` / `password`

## Struktur

```
├── app/
│   ├── Filament/Admin/          # Admin panel
│   │   ├── Resources/Submissions/
│   │   │   ├── Tables/          # Table config + CSV export
│   │   │   └── Schemas/         # Infolist + Form
│   │   └── Widgets/             # Dashboard widgets
│   ├── Http/Controllers/        # QuizController (AJAX submit)
│   └── Models/Submission.php    # Eloquent model
├── resources/views/quiz/
│   └── index.blade.php          # SPA quiz + IG export card
├── public/images/
│   └── plai-bmd-logo.png        # Logo kampus
└── database/migrations/         # Submissions table
```

## License

MIT

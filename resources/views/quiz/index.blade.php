<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minat Meter — PLAI BMD</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/plai-bmd-logo.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap');

        :root {
            --primary: #D4A017;
            --primary-dark: #B8860B;
            --primary-light: #F5D060;
            --accent: #1a1a1a;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #f0f2f5;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Fredoka', 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        .hero {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white; text-align: center; padding: 2rem 1rem 1.5rem;
        }
        .hero-logo { height: 56px; margin-bottom: 0.6rem; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4)); }
        .hero h1 { font-size: 2rem; font-weight: 900; color: var(--primary); }
        .hero p { font-size: 0.95rem; opacity: 0.85; margin-top: 0.25rem; }

        .steps-bar {
            display: flex; justify-content: center; gap: 0; padding: 0.7rem 1rem;
            background: white; border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }
        .step-item {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.45rem 1rem; font-size: 0.85rem; font-weight: 600; color: var(--muted);
        }
        .step-item:not(:last-child)::after { content: '→'; margin-left: 0.4rem; color: var(--border); font-size: 1.1rem; }
        .step-item.active { color: var(--primary-dark); }
        .step-item.done { color: var(--success); }
        .step-num {
            width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; background: var(--border); color: var(--muted);
        }
        .step-item.active .step-num { background: var(--primary); color: white; }
        .step-item.done .step-num { background: var(--success); color: white; }

        .content { max-width: 720px; margin: 0 auto; padding: 1.5rem 1.5rem 3rem; }
        @media (min-width: 1024px) { .content { max-width: 780px; padding: 2rem 2rem 3rem; } }

        .card {
            background: white; border-radius: 18px; padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 1rem;
        }
        .card-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.25rem; }
        .card-desc { font-size: 0.88rem; color: var(--muted); margin-bottom: 1rem; }

        .form-group { margin-bottom: 0.85rem; }
        .form-group label { display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.3rem; }
        .form-group label .req { color: var(--danger); }
        .form-control {
            width: 100%; padding: 0.6rem 0.85rem; border: 1.5px solid var(--border); border-radius: 10px;
            font-size: 0.9rem; font-family: inherit; outline: none; transition: border-color 0.2s;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(212,160,23,0.12); }
        .form-control.error { border-color: var(--danger); }
        textarea.form-control { resize: vertical; min-height: 65px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .form-check {
            display: flex; align-items: flex-start; gap: 0.5rem; padding: 0.6rem;
            background: #f8f8f8; border-radius: 10px; cursor: pointer;
        }
        .form-check input[type="checkbox"] { width: 18px; height: 18px; margin-top: 2px; accent-color: var(--primary); }
        .form-check span { font-size: 0.85rem; }
        .error-msg { font-size: 0.75rem; color: var(--danger); margin-top: 0.2rem; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; border: none; border-radius: 12px;
            font-size: 0.92rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.2s;
        }
        .btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .btn-primary { background: var(--primary); color: white; width: 100%; }
        .btn-primary:hover:not(:disabled) { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-dark { background: var(--accent); color: white; width: 100%; }
        .btn-dark:hover:not(:disabled) { background: #333; }
        .btn-outline { background: transparent; color: var(--primary-dark); border: 1.5px solid var(--primary); }
        .btn-outline:hover { background: rgba(212,160,23,0.06); }
        .btn-success { background: var(--success); color: white; width: 100%; }
        .btn-success:hover:not(:disabled) { background: #059669; }
        .btn-row { display: flex; gap: 0.75rem; margin-top: 1rem; }
        .btn-row .btn { flex: 1; }

        .quiz-progress-wrap { margin-bottom: 1rem; }
        .quiz-progress-info { display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--muted); margin-bottom: 0.35rem; }
        .quiz-progress-bar { height: 6px; background: var(--border); border-radius: 10px; overflow: hidden; }
        .quiz-progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--primary-light)); border-radius: 10px; transition: width 0.3s; }

        .question-single {
            background: white; border-radius: 20px; padding: 2rem; text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06); margin-bottom: 1rem;
            min-height: 320px; display: flex; flex-direction: column; justify-content: center;
        }
        @media (min-width: 1024px) { .question-single { padding: 2.5rem 3rem; min-height: 360px; } }

        .q-section-badge {
            display: inline-block; padding: 0.3rem 0.9rem; border-radius: 20px;
            font-size: 0.78rem; font-weight: 600; margin-bottom: 1rem;
            background: #fef3c7; color: #92400e;
        }
        .q-number-big { font-size: 0.82rem; color: var(--muted); font-weight: 500; margin-bottom: 0.6rem; }
        .q-text-big { font-size: 1.15rem; font-weight: 600; line-height: 1.6; margin-bottom: 1.5rem; color: var(--text); }
        @media (min-width: 1024px) { .q-text-big { font-size: 1.25rem; } }

        .likert-big { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.6rem; }
        @media (min-width: 1024px) { .likert-big { gap: 0.8rem; } }

        .likert-big-btn {
            display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
            padding: 0.8rem 0.4rem; border: 2.5px solid var(--border); border-radius: 14px;
            background: white; cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        @media (min-width: 1024px) { .likert-big-btn { padding: 1rem 0.5rem; } }
        .likert-big-btn:hover { border-color: var(--primary-light); background: #fffbeb; transform: translateY(-2px); }
        .likert-big-btn.selected {
            border-color: var(--primary); background: #fef3c7;
            box-shadow: 0 4px 12px rgba(212,160,23,0.25); transform: scale(1.05);
        }
        .likert-big-btn .emoji { font-size: 1.8rem; }
        @media (min-width: 1024px) { .likert-big-btn .emoji { font-size: 2.2rem; } }
        .likert-big-btn .num { font-size: 0.82rem; font-weight: 700; color: var(--muted); }
        .likert-big-btn .label { font-size: 0.68rem; color: var(--muted); text-align: center; line-height: 1.2; }
        .likert-big-btn.selected .num { color: var(--primary-dark); }
        .likert-big-btn.selected .label { color: var(--primary-dark); font-weight: 600; }

        .result-hero {
            text-align: center; padding: 2rem 1.5rem; border-radius: 20px;
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white; margin-bottom: 1.5rem; position: relative; overflow: hidden;
        }
        .result-hero .logo-small { height: 40px; margin-bottom: 0.6rem; }
        .result-hero h2 { font-size: 1.5rem; font-weight: 900; color: var(--primary); }
        .result-hero p { opacity: 0.85; margin-top: 0.3rem; font-size: 0.9rem; }

        .score-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.8rem; margin-bottom: 1.5rem; }
        .score-card {
            text-align: center; padding: 1.2rem 0.5rem; border-radius: 16px;
            background: white; border: 2.5px solid var(--border); transition: all 0.3s;
        }
        .score-card.winner {
            border-color: var(--primary); background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            transform: scale(1.04); box-shadow: 0 6px 16px rgba(212,160,23,0.25);
        }
        .score-card .emoji { font-size: 1.8rem; margin-bottom: 0.3rem; }
        .score-card .score-val { font-size: 1.6rem; font-weight: 900; }
        .score-card .score-label { font-size: 0.75rem; color: var(--muted); margin-top: 0.15rem; font-weight: 500; }
        .score-card.winner .score-val { color: var(--primary-dark); }

        .rec-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2.5px solid var(--primary); border-radius: 18px; padding: 1.5rem;
            text-align: center; margin-bottom: 1.5rem;
        }
        .rec-box h3 { font-size: 1rem; color: #78350f; margin-bottom: 0.3rem; }
        .rec-box .prodi-name { font-size: 1.4rem; font-weight: 900; color: var(--primary-dark); }

        .resume-card { background: white; border-radius: 14px; border: 1.5px solid var(--border); overflow: hidden; margin-bottom: 1rem; }
        .resume-header { background: #f8f8f8; padding: 0.7rem 1.2rem; font-weight: 700; font-size: 0.9rem; border-bottom: 1px solid var(--border); }
        .resume-body { padding: 0.9rem 1.2rem; }
        .resume-row { display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.85rem; border-bottom: 1px solid #f5f5f5; }
        .resume-row:last-child { border-bottom: none; }
        .resume-row .label { color: var(--muted); }
        .resume-row .value { font-weight: 600; text-align: right; }

        .contact-card { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 14px; padding: 1.2rem; text-align: center; }
        .contact-card h4 { font-size: 0.92rem; font-weight: 700; color: #78350f; margin-bottom: 0.4rem; }
        .contact-item { font-size: 0.85rem; color: #78350f; margin: 0.25rem 0; }

        /* ===== IG EXPORT CARD - MATCHES REFERENCE EXACTLY ===== */
        .export-card {
            width: 1080px; height: 1080px;
            background: #2a2a2a;
            padding: 0; color: white; position: relative; overflow: hidden;
            font-family: 'Inter', sans-serif; display: flex; flex-direction: column;
        }
        /* Gold glow top-right */
        .export-card::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(212,160,23,0.35) 0%, rgba(212,160,23,0.1) 40%, transparent 70%);
            border-radius: 50%;
        }

        .export-content {
            flex: 1; display: flex; flex-direction: column; justify-content: center;
            padding: 80px 70px; position: relative; z-index: 1;
        }

        /* Header kampus di atas */
        .export-header {
            display: flex; align-items: center; gap: 16px; margin-bottom: 50px;
        }
        .export-header-logo { height: 50px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); }
        .export-kampus-name { font-size: 20px; font-weight: 700; color: #D4A017; letter-spacing: 0.5px; }
        .export-kampus-tagline { font-size: 14px; color: #999; margin-top: 2px; }

        .export-line1 {
            font-size: 28px; color: #ccc; margin-bottom: 4px; font-weight: 400;
            line-height: 1.4;
        }
        .export-line1 strong {
            color: #D4A017; font-weight: 700;
        }

        .export-line2 {
            font-size: 22px; color: #999; margin-bottom: 6px; font-weight: 400;
            margin-top: 30px;
        }

        .export-prodi {
            font-size: 90px; font-weight: 900; color: #D4A017; line-height: 1.05;
            letter-spacing: -2px; margin-bottom: 40px;
        }

        .export-divider {
            width: 80px; height: 3px; background: #D4A017; margin-bottom: 40px;
        }

        .export-cta {
            display: flex; align-items: center; gap: 20px;
        }
        .export-arrow {
            width: 56px; height: 56px; background: #D4A017; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .export-arrow svg { width: 28px; height: 28px; }
        .export-cta-text { font-size: 16px; color: #999; line-height: 1.5; }
        .export-cta-text strong { color: #D4A017; font-weight: 700; font-size: 18px; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeInUp 0.35s ease forwards; }
        @keyframes pop { 0% { transform: scale(0.85); opacity: 0; } 50% { transform: scale(1.03); } 100% { transform: scale(1); opacity: 1; } }
        .animate-pop { animation: pop 0.45s ease forwards; }
        @keyframes bounce-in { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.08); } 70% { transform: scale(0.95); } 100% { transform: scale(1); opacity: 1; } }
        .animate-bounce { animation: bounce-in 0.6s ease forwards; }
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        .confetti { position: fixed; top: -10px; z-index: 100; animation: confetti-fall linear forwards; }
        @media print {
            body * { visibility: hidden; }
            #export-area, #export-area * { visibility: visible; }
            #export-area { position: absolute; left: 0; top: 0; }
        }
    </style>
</head>
<body>
<div x-data="quizApp()" x-init="init()">

    <template x-if="step === 3">
        <div>
            <template x-for="c in confetti" :key="c.id">
                <div class="confetti" :style="`left:${c.x}%;background:${c.color};width:${c.size}px;height:${c.size}px;border-radius:${c.round?'50%':'2px'};animation-duration:${c.dur}s;animation-delay:${c.delay}s;`"></div>
            </template>
        </div>
    </template>

    <div class="hero">
        <img src="{{ asset('images/plai-bmd-logo.png') }}" alt="PLAI BMD" class="hero-logo">
        <h1>🎯 Minat Meter</h1>
        <p>Sains Data Terapan • AI & Robotika • Keamanan Siber</p>
    </div>

    <div class="steps-bar">
        <div class="step-item" :class="{ active: step === 1, done: step > 1 }">
            <div class="step-num" x-text="step > 1 ? '✓' : '1'"></div><span>Data Diri</span>
        </div>
        <div class="step-item" :class="{ active: step === 2, done: step > 2 }">
            <div class="step-num" x-text="step > 2 ? '✓' : '2'"></div><span>Tes Minat</span>
        </div>
        <div class="step-item" :class="{ active: step === 3 }">
            <div class="step-num">3</div><span>Hasil</span>
        </div>
    </div>

    <div class="content">

        <!-- STEP 1 -->
        <div x-show="step === 1" x-transition class="animate-in">
            <div class="card">
                <div class="card-title">📝 Data Diri</div>
                <div class="card-desc">Isi data dirimu dulu ya!</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="req">*</span></label>
                        <input type="text" class="form-control" :class="{ error: errors.nama }" x-model="form.nama" placeholder="Nama kamu" @input="errors.nama=''">
                        <div class="error-msg" x-show="errors.nama" x-text="errors.nama"></div>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="req">*</span></label>
                        <input type="email" class="form-control" :class="{ error: errors.email }" x-model="form.email" placeholder="nama@email.com" @input="errors.email=''">
                        <div class="error-msg" x-show="errors.email" x-text="errors.email"></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>WhatsApp <span class="req">*</span></label>
                        <input type="tel" class="form-control" :class="{ error: errors.whatsapp }" x-model="form.whatsapp" placeholder="081234567890" @input="errors.whatsapp=''">
                        <div class="error-msg" x-show="errors.whatsapp" x-text="errors.whatsapp"></div>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah</label>
                        <input type="text" class="form-control" x-model="form.asal_sekolah" placeholder="SMA/SMK ...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" class="form-control" x-model="form.kota" placeholder="Kota">
                    </div>
                    <div class="form-group">
                        <label>Usia</label>
                        <select class="form-control" x-model="form.usia">
                            <option value="">Pilih</option>
                            <template x-for="u in [15,16,17,18,19,20]" :key="u">
                                <option :value="u" x-text="u + ' tahun'"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" x-model="form.izin_dihubungi">
                        <span>Setuju dihubungi untuk info kampus & beasiswa</span>
                    </label>
                </div>
                <button class="btn btn-primary" @click="goToQuiz()">Lanjut ke Tes Minat →</button>
            </div>
        </div>

        <!-- STEP 2 -->
        <div x-show="step === 2" x-transition class="animate-in">
            <div class="quiz-progress-wrap">
                <div class="quiz-progress-info">
                    <span x-text="`Pertanyaan ${currentQ + 1} dari 15`"></span>
                    <span x-text="Math.round(((currentQ + 1)/15)*100) + '%'"></span>
                </div>
                <div class="quiz-progress-bar">
                    <div class="quiz-progress-fill" :style="`width: ${((currentQ + 1)/15)*100}%`"></div>
                </div>
            </div>
            <div class="question-single animate-in" :key="currentQ">
                <div class="q-section-badge" x-text="getSectionLabel(questions[currentQ].section)"></div>
                <div class="q-number-big" x-text="'Pertanyaan ' + (currentQ + 1) + ' dari 15'"></div>
                <div class="q-text-big" x-text="questions[currentQ].text"></div>
                <div class="likert-big">
                    <template x-for="opt in likertOptions" :key="'q'+currentQ+opt.value">
                        <button class="likert-big-btn"
                                :class="{ selected: answers[questions[currentQ].id] === opt.value }"
                                @click="selectAnswer(questions[currentQ].id, opt.value)">
                            <span class="emoji" x-text="opt.emoji"></span>
                            <span class="num" x-text="opt.value"></span>
                            <span class="label" x-text="opt.label"></span>
                        </button>
                    </template>
                </div>
            </div>
            <div class="btn-row">
                <button class="btn btn-outline" @click="currentQ > 0 ? currentQ-- : step=1" x-text="currentQ === 0 ? '← Kembali' : '← Sebelumnya'"></button>
                <button class="btn btn-dark" x-show="currentQ < 14" :disabled="!answers[questions[currentQ].id]" @click="currentQ++">Selanjutnya →</button>
                <button class="btn btn-success" x-show="currentQ === 14" :disabled="answeredCount < 15" @click="submitQuiz()">🎯 Lihat Hasil</button>
            </div>
        </div>

        <!-- STEP 3 -->
        <div x-show="step === 3" x-transition class="animate-in">
            <div class="result-hero animate-pop">
                <img src="{{ asset('images/plai-bmd-logo.png') }}" alt="PLAI BMD" class="logo-small">
                <h2>🎉 Hasil Tes Minat Kamu!</h2>
                <p>Temukan prodi yang cocok dengan minatmu di PLAI BMD</p>
            </div>
            <div class="score-cards">
                <div class="score-card" :class="{ winner: isWinner('sainsdata') }">
                    <div class="emoji">📈</div>
                    <div class="score-val" x-text="results.scores.sainsdata.toFixed(0) + '%'"></div>
                    <div class="score-label">Sains Data</div>
                </div>
                <div class="score-card" :class="{ winner: isWinner('ai_robotika') }">
                    <div class="emoji">🤖</div>
                    <div class="score-val" x-text="results.scores.ai_robotika.toFixed(0) + '%'"></div>
                    <div class="score-label">AI & Robotika</div>
                </div>
                <div class="score-card" :class="{ winner: isWinner('keamanan') }">
                    <div class="emoji">🛡️</div>
                    <div class="score-val" x-text="results.scores.keamanan.toFixed(0) + '%'"></div>
                    <div class="score-label">Keamanan Siber</div>
                </div>
            </div>
            <div class="rec-box animate-bounce">
                <h3>🏆 Rekomendasi Prodi</h3>
                <div class="prodi-name" x-text="results.rekomendasi"></div>
            </div>
            <div class="resume-card">
                <div class="resume-header">📋 Resume Jawaban</div>
                <div class="resume-body">
                    <div class="resume-row"><span class="label">Nama</span><span class="value" x-text="form.nama"></span></div>
                    <div class="resume-row"><span class="label">Sains Data</span><span class="value" x-text="results.scores.sainsdata.toFixed(1) + '%'"></span></div>
                    <div class="resume-row"><span class="label">AI & Robotika</span><span class="value" x-text="results.scores.ai_robotika.toFixed(1) + '%'"></span></div>
                    <div class="resume-row"><span class="label">Keamanan Siber</span><span class="value" x-text="results.scores.keamanan.toFixed(1) + '%'"></span></div>
                </div>
            </div>
            <div class="contact-card">
                <h4>🚀 Hubungi PLAI BMD</h4>
                <div class="contact-item">📞 <strong>0817-5152-251</strong></div>
                <div class="contact-item">✉️ <strong>humas@plai.ac.id</strong></div>
            </div>
            <div class="btn-row" style="margin-top: 1.2rem;">
                <button class="btn btn-primary" @click="downloadImage()">📸 Download Gambar IG</button>
            </div>
            <div class="btn-row">
                <button class="btn btn-dark" @click="resetQuiz()">🔄 Ulangi Tes</button>
            </div>
        </div>
    </div>

    <!-- ===== HIDDEN EXPORT CARD - PERSIS SESUAI REFERENSI ===== -->
    <div id="export-area" style="position:absolute;left:-9999px;top:0;">
        <div class="export-card" id="export-card">
            <div class="export-content">
                <!-- Header kampus -->
                <div class="export-header">
                    <img src="{{ asset('images/plai-bmd-logo.png') }}" alt="PLAI BMD" class="export-header-logo">
                    <div class="export-header-text">
                        <div class="export-kampus-name">Politeknik AI Budi Mulia Dua</div>
                        <div class="export-kampus-tagline">Kampus AI pertama di Indonesia</div>
                    </div>
                </div>
                <div class="export-line1">
                    Hai nama saya <strong x-text="form.nama"></strong>, aku habis coba<br>
                    <strong>minat meter PLAI BMD.</strong>
                </div>

                <div class="export-line2">Ternyata minatku di</div>

                <div class="export-prodi" x-text="getProdiShort(results.rekomendasi)"></div>

                <div class="export-divider"></div>

                <div class="export-cta">
                    <div class="export-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                    <div class="export-cta-text">
                        Sekarang giliran kamu.<br>
                        <strong>Yuk cek di www.plai.ac.id</strong><br>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function quizApp() {
    return {
        step: 1,
        currentQ: 0,
        form: { nama: '', email: '', whatsapp: '', asal_sekolah: '', kota: '', usia: '', minat_lain: '', izin_dihubungi: true },
        errors: {},
        answers: {},
        results: { scores: { sainsdata: 0, ai_robotika: 0, keamanan: 0 }, rekomendasi: '' },
        confetti: [],

        questions: [
            { id: 1, section: 'A', text: 'Saya suka teka-teki logika dan mencari pola dalam data/angka' },
            { id: 2, section: 'A', text: 'Saya penasaran bagaimana komputer bisa mengenali gambar, suara, atau teks' },
            { id: 3, section: 'A', text: 'Saya suka bongkar pasang alat atau membuat alat yang bisa bergerak dengan sensor' },
            { id: 4, section: 'A', text: 'Saya senang mencari celah keamanan dan memikirkan cara mengamankan sistem' },
            { id: 5, section: 'B', text: 'Saya suka membuat grafik/cerita dari data agar mudah dipahami orang lain' },
            { id: 6, section: 'B', text: 'Saya ingin membuat program yang bisa belajar sendiri (machine learning)' },
            { id: 7, section: 'B', text: 'Saya ingin robot bisa bergerak sesuai perintah yang saya tulis dalam kode' },
            { id: 8, section: 'B', text: 'Saya ingin menjaga sistem tetap aman: password, jaringan, dan data' },
            { id: 9, section: 'C', text: 'Saya teliti dan suka mengecek ulang sebelum mengirim hasil kerja' },
            { id: 10, section: 'C', text: 'Saya nyaman dengan matematika dasar seperti aljabar, statistik, dan peluang' },
            { id: 11, section: 'C', text: 'Saya suka ngoprek elektronik: sensor, motor, Arduino, atau Raspberry Pi' },
            { id: 12, section: 'C', text: 'Saya senang bereksperimen: mencoba berbagai cara sampai menemukan yang terbaik' },
            { id: 13, section: 'C', text: 'Saya peduli dengan privasi data & etika dalam penggunaan teknologi' },
            { id: 14, section: 'D', text: 'Saya tertarik membuat sistem prediksi (cuaca, harga, nilai akademik)' },
            { id: 15, section: 'D', text: 'Saya ingin membuat alat otomatis (pintu otomatis, line follower, smart home)' },
        ],

        likertOptions: [
            { value: 1, emoji: '😕', label: 'Tidak Suka' },
            { value: 2, emoji: '🙁', label: 'Kurang Suka' },
            { value: 3, emoji: '😐', label: 'Netral' },
            { value: 4, emoji: '🙂', label: 'Suka' },
            { value: 5, emoji: '😍', label: 'Sangat Suka' },
        ],

        get answeredCount() { return Object.values(this.answers).filter(v => v !== undefined).length; },

        getSectionLabel(s) {
            const labels = { A: '🧠 Cara Berpikir & Eksplorasi', B: '💫 Hal yang Membuat Semangat', C: '⚡ Kebiasaan & Gaya Kerja', D: '🚀 Minat Proyek Nyata' };
            return labels[s] || '';
        },

        getProdiShort(rekom) {
            if (!rekom) return '';
            if (rekom.includes('AI')) return 'AI & ROBOTIKA';
            if (rekom.includes('Sains')) return 'SAINS DATA';
            if (rekom.includes('Keamanan')) return 'KEAMANAN SIBER';
            return rekom.toUpperCase();
        },

        init() { for (let i = 1; i <= 15; i++) this.answers[i] = undefined; },

        selectAnswer(qId, val) {
            this.answers[qId] = val;
            setTimeout(() => { if (this.currentQ < 14) this.currentQ++; }, 300);
        },

        goToQuiz() {
            this.errors = {};
            if (!this.form.nama.trim()) { this.errors.nama = 'Nama wajib diisi.'; return; }
            if (!this.form.email.trim() || !this.form.email.includes('@')) { this.errors.email = 'Email tidak valid.'; return; }
            if (!this.form.whatsapp.trim() || !this.form.whatsapp.startsWith('0') || this.form.whatsapp.length < 10) { this.errors.whatsapp = 'WhatsApp harus 08xxxxxxxxxx.'; return; }
            this.step = 2; this.currentQ = 0;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        isWinner(key) {
            const s = this.results.scores;
            const max = Math.max(s.sainsdata, s.ai_robotika, s.keamanan);
            return s[key] === max;
        },

        async submitQuiz() {
            if (this.answeredCount < 15) return;
            const payload = {
                nama: this.form.nama, email: this.form.email, whatsapp: this.form.whatsapp,
                asal_sekolah: this.form.asal_sekolah, kota: this.form.kota,
                usia: this.form.usia || null, minat_lain: this.form.minat_lain,
                izin_dihubungi: this.form.izin_dihubungi ? 1 : 0,
            };
            for (let i = 1; i <= 15; i++) payload['q' + i] = this.answers[i];
            try {
                const res = await fetch('{{ route("quiz.submit") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (data.success) {
                    this.results = {
                        scores: {
                            sainsdata: data.data.scores['Sains Data Terapan'] || 0,
                            ai_robotika: data.data.scores['AI & Robotika'] || 0,
                            keamanan: data.data.scores['Rekayasa Keamanan Siber'] || 0,
                        },
                        rekomendasi: data.data.rekomendasi,
                    };
                    this.step = 3;
                    this.spawnConfetti();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else if (data.errors) { alert('Error: ' + JSON.stringify(data.errors)); }
            } catch (e) { alert('Gagal mengirim: ' + e.message); }
        },

        spawnConfetti() {
            const colors = ['#D4A017', '#F5D060', '#1a1a1a', '#ef4444', '#10b981', '#3b82f6', '#ec4899'];
            this.confetti = Array.from({ length: 50 }, (_, i) => ({
                id: i, x: Math.random() * 100, color: colors[Math.floor(Math.random() * colors.length)],
                size: Math.random() * 8 + 5, round: Math.random() > 0.5,
                dur: Math.random() * 2 + 2, delay: Math.random() * 1.5,
            }));
            setTimeout(() => this.confetti = [], 4000);
        },

        async downloadImage() {
            const el = document.getElementById('export-card');
            const wrapper = el.parentElement;
            wrapper.style.position = 'fixed';
            wrapper.style.left = '0';
            wrapper.style.top = '0';
            wrapper.style.zIndex = '9999';
            await new Promise(r => setTimeout(r, 300));
            try {
                const canvas = await html2canvas(el, {
                    width: 1080, height: 1080, scale: 1,
                    backgroundColor: '#2a2a2a', useCORS: true,
                });
                const link = document.createElement('a');
                link.download = `minat-meter-${this.form.nama.replace(/\s+/g, '-').toLowerCase()}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            } catch (e) { alert('Gagal: ' + e.message); }
            wrapper.style.position = 'absolute';
            wrapper.style.left = '-9999px';
            wrapper.style.zIndex = '';
        },

        resetQuiz() {
            this.step = 1; this.currentQ = 0;
            this.answers = {};
            for (let i = 1; i <= 15; i++) this.answers[i] = undefined;
            this.results = { scores: { sainsdata: 0, ai_robotika: 0, keamanan: 0 }, rekomendasi: '' };
            this.confetti = [];
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
}
</script>
</body>
</html>

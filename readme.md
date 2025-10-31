# Tes Cocok Prodi — Sains Data • AI • Robotik • Rekayasa Keamanan Siber

Aplikasi **kuis interaktif** berbasis Streamlit untuk membantu siswa SMA menemukan **kecocokan prodi** (Sains Data, AI, Robotik, atau Rekayasa Keamanan Siber).  
Aplikasi meminta data prospek (nama, email, WhatsApp, asal sekolah, kota) sebelum kuis, lalu memberikan **skor & rekomendasi** dengan bahasa yang mudah dipahami.

---

## ✨ Fitur Utama
- Form prospek: **nama, email, WhatsApp, asal sekolah, kota, izin dihubungi**.
- **15 pertanyaan** skala 1–5 (bahasa santai).
- Skor & rekomendasi untuk **Sains Data, AI, Robotik, Keamanan Siber**.
- UI interaktif: **metric, badge, balloons, expander**, ringkasan hasil yang bisa dicopy.
- **Penyimpanan hasil** otomatis ke `submissions.csv` (mudah diimpor ke Excel/Google Sheets).
- Tombol **Ulangi Tes** untuk reset sesi.

---

## 🧩 Cara Kerja & Logika Skor (Singkat)
- Setiap pertanyaan memetakan minat/kemampuan ke bidang tertentu.
- Pembobotan sederhana, contoh:
  - **Sains Data**: logika & pola (Q1), storytelling data (Q5), ketelitian (Q9), matematika dasar (Q10), eksperimen (Q12), prediksi (Q14).
  - **AI**: rasa ingin tahu ML (Q2, Q6), matematika dasar (Q10), eksperimen (Q12), prediksi (Q14).
  - **Robotik**: hardware & sensor (Q3, Q7, Q11), otomasi alat (Q15).
  - **Keamanan Siber**: mindset proteksi (Q4, Q8), ketelitian (Q9), etika/privasi (Q13).
- Rekomendasi adalah **skor tertinggi** (atau gabungan kalau seri).

---

## 📦 Spesifikasi & Kebutuhan Sistem

### Perangkat Lunak
- **Python**: 3.9 – 3.12
- **Pip** untuk instalasi paket
- **Streamlit** (lihat `requirements.txt`)

### Perangkat Keras (minimal)
- CPU dual-core
- RAM 2 GB (4 GB disarankan)
- Penyimpanan bebas ≥ 200 MB

### Jaringan
- Lokal tanpa internet **bisa** (tidak ada dependensi API eksternal)
- Akses browser ke `http://localhost:8501`

---

## 📁 Struktur Berkas
```
your-folder/
├─ streamlit_app.py       # aplikasi utama
├─ requirements.txt       # dependensi
├─ README.md              # dokumen ini
└─ submissions.csv        # akan dibuat otomatis saat ada hasil pertama
```

---

## 🪟 Instalasi & Menjalankan (Windows)

> Direkomendasikan menggunakan **PowerShell**.
```powershell
# 1) Masuk ke folder proyek
cd PATH\ke\folder\proyek

# 2) (Opsional) Buat virtual environment
py -m venv .venv
.\.venv\Scripts\Activate.ps1

# 3) Install dependensi
pip install -r requirements.txt

# 4) Jalankan aplikasi
streamlit run streamlit_app.py

# 5) Buka di browser: http://localhost:8501
```

### Catatan Windows
- Jika eksekusi skrip diblokir, jalankan PowerShell sebagai Administrator lalu:
  ```powershell
  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
  ```
- Nonaktifkan venv:
  ```powershell
  deactivate
  ```

---

## 🐧 Instalasi & Menjalankan (Ubuntu/Debian)

```bash
# 1) Perbarui sistem (opsional)
sudo apt update

# 2) Pastikan Python & pip tersedia
python3 --version
python3 -m pip --version || sudo apt install -y python3-pip

# 3) (Opsional) Buat virtual environment
python3 -m venv .venv
source .venv/bin/activate

# 4) Install dependensi
pip install -r requirements.txt

# 5) Jalankan aplikasi
streamlit run streamlit_app.py

# 6) Buka di browser: http://localhost:8501
```

### Jalankan di port tertentu (opsional)
```bash
streamlit run streamlit_app.py --server.port 8502 --server.address 0.0.0.0
```

---

## 🧪 Pengujian Cepat
- Isi form prospek (validasi email & WhatsApp dengan format `+62...`).
- Jawab seluruh 15 pertanyaan → klik **Lihat Hasil**.
- Lihat skor, rekomendasi, dan file `submissions.csv` terbuat/terisi.

---

## 🔐 Privasi & Keamanan Data
- Data prospek disimpan **lokal** di `submissions.csv`.
- Pindahkan file ke penyimpanan aman sesuai kebijakan kampus.
- Hapus/anonimkan data kalau diminta peserta.

---

## 🛠️ Kustomisasi Cepat
- **Pertanyaan/Bobot**: ubah bagian `# Skor per prodi` dan daftar pertanyaan di `streamlit_app.py`.
- **Branding**: ganti judul, emoji, dan warna badge (fungsi `badge()`).
- **Integrasi Sheets** (opsional): kirim hasil ke Google Sheets dengan `gspread` (tidak termasuk default).
- **Bahasa Inggris**: duplikasi teks UI dan sediakan toggle.

---

## ❗ Troubleshooting
- **Port sudah dipakai**: ganti port `--server.port 8502` atau tutup proses di 8501.
- **ModuleNotFoundError**: pastikan `pip install -r requirements.txt` di **venv yang aktif**.
- **Permission error saat tulis CSV**: jalankan di folder dengan izin tulis atau ubah lokasi `SUBMISSION_FILE`.
- **Emoji tidak tampil**: coba browser lain atau sistem font berbeda.

---


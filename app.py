# streamlit_app.py
# ---------------------------------------------------------------
# "Tes Cocok Prodi" — untuk Sains Data, AI, Robotik, Keamanan Siber
# Bahasa ringan buat siswa SMA. Menyimpan prospek (nama/email/WhatsApp).
# Jalankan: streamlit run streamlit_app.py
# ---------------------------------------------------------------

import streamlit as st
from datetime import datetime
import csv
import os

st.set_page_config(
    page_title="Tes Cocok Prodi (Sains Data • AI • Robotik • Rekayasa Keamanan Siber)",
    page_icon="🎯",
    layout="centered"
)

# --------------------------- Helpers ---------------------------

SUBMISSION_FILE = "submissions.csv"

def init_storage():
    # Buat file CSV jika belum ada
    if not os.path.exists(SUBMISSION_FILE):
        with open(SUBMISSION_FILE, mode="w", newline="", encoding="utf-8") as f:
            writer = csv.writer(f)
            header = [
                "timestamp","nama","email","whatsapp","asal_sekolah","kota",
                "izin_dihubungi","usia","minat_lain",
                # 15 jawaban
                *[f"Q{i}" for i in range(1,16)],
                "skor_sainsdata","skor_ai","skor_robotik","skor_keamanan",
                "rekomendasi"
            ]
            writer.writerow(header)

def save_submission(row):
    # Tambahkan baris ke CSV
    with open(SUBMISSION_FILE, mode="a", newline="", encoding="utf-8") as f:
        writer = csv.writer(f)
        writer.writerow(row)

def is_valid_email(email: str) -> bool:
    return "@" in email and "." in email and len(email) <= 120

def is_valid_whatsapp(wa: str) -> bool:
    wa = wa.strip().replace(" ", "").replace("-", "")
    return wa.startswith("+") and wa[1:].isdigit() and 9 <= len(wa) <= 16

def likert(label, key):
    return st.slider(label, 1, 5, 3, key=key, help="1 = Tidak banget, 5 = Banget banget")

def badge(text):
    st.markdown(f"<span style='padding:4px 10px;border-radius:999px;background:#EEF6FF;color:#1E40AF;font-weight:600'>{text}</span>",
                unsafe_allow_html=True)

# ------------------------- Sidebar Info ------------------------
with st.sidebar:
    st.header("Tentang Pilihan Prodi")
    st.write("Pakai bahasa santai, biar gampang kebayang:")
    st.markdown("""
**Sains Data (Data Science)**  
Bikin data jadi cerita & keputusan. Ngulik pola, grafik, dan prediksi.

**Kecerdasan Buatan (AI)**  
Bikin program yang *belajar sendiri*: gambar dikenali, suara dimengerti, teks dipahami.

**Robotik**  
Bikin benda bergerak otomatis pakai sensor, motor, dan kode.

**Rekayasa Keamanan Siber**  
Jaga sistem biar aman: uji celah, enkripsi, *ethical hacking*, dan kebijakan keamanan.
""")
    st.caption("Tips: Jawab sejujur-jujurnya. Nggak ada yang salah—semua bisa dipelajari.")

# --------------------------- Header ----------------------------
st.title("🎯 Tes Cocok Prodi: Sains Data • AI • Robotik • Keamanan Siber")
st.write("Pakai bahasa mudah. Kamu jawab 15 pertanyaan singkat, terus kami kasih rekomendasi prodi yang paling cocok.")

init_storage()

# ------------------------ Step 1: Lead Form --------------------
st.subheader("📝 Langkah 1 — Isi data singkat dulu")
with st.form("lead_form", clear_on_submit=False):
    col1, col2 = st.columns(2)
    with col1:
        nama = st.text_input("Nama lengkap", placeholder="Nama kamu")
        email = st.text_input("Email aktif", placeholder="nama@email.com")
        usia = st.number_input("Usia", min_value=12, max_value=60, value=17, step=1)
    with col2:
        whatsapp = st.text_input("Nomor WhatsApp (pakai kode negara)", placeholder="+62xxxxxxxxxxx")
        asal_sekolah = st.text_input("Asal sekolah", placeholder="SMA/SMK/MA ...")
        kota = st.text_input("Kota/Kabupaten", placeholder="Misal: Sleman")
    izin_dihubungi = st.checkbox("Saya setuju dihubungi untuk info beasiswa, event, dan pendaftaran.", value=True)
    minat_lain = st.text_area("Tulis minat/pertanyaan kamu (opsional)", placeholder="Misal: suka desain, robot line follower, editing video...")

    submitted_lead = st.form_submit_button("Lanjut ke Kuis ▶️")

if submitted_lead:
    # Validasi sederhana
    errors = []
    if not nama.strip():
        errors.append("Nama wajib diisi.")
    if not is_valid_email(email):
        errors.append("Email kurang valid.")
    if not is_valid_whatsapp(whatsapp):
        errors.append("Nomor WhatsApp harus pakai kode negara, misal +62...")

    if errors:
        for e in errors:
            st.error(e)
    else:
        st.session_state["lead_ok"] = True
        st.success("Data tersimpan sementara. Yuk lanjut ke kuis!")

# ------------------------ Step 2: Quiz -------------------------
if st.session_state.get("lead_ok", False):
    st.subheader("🧩 Langkah 2 — Jawab 15 Pertanyaan (1 = tidak banget, 5 = banget banget)")
    st.caption("Jawab pakai perasaan kamu. Bukan ujian kok 🙂")

    with st.form("quiz_form"):
        # 15 pertanyaan dengan bahasa santai
        st.markdown("### A. Cara kamu mikir & suka ngulik")
        Q1 = likert("Aku suka **teka-teki logika** dan cari pola di data/angka.", "Q1")  # DS
        Q2 = likert("Aku penasaran gimana **komputer bisa mengenali gambar/suara/teks**.", "Q2")  # AI
        Q3 = likert("Aku suka **bongkar pasang alat** atau bikin alat gerak pakai sensor.", "Q3")  # ROB
        Q4 = likert("Aku senang **mencari celah keamanan** dan mikir cara **mengamankan** sistem.", "Q4")  # SEC

        st.markdown("### B. Hal yang bikin kamu semangat")
        Q5 = likert("Aku suka **bikin grafik/cerita dari data** biar orang lain paham.", "Q5")  # DS
        Q6 = likert("Aku pengin **bikin program yang belajar sendiri** (machine learning).", "Q6")  # AI
        Q7 = likert("Aku pengin **robot jalan sesuai perintah** yang kutulis di kode.", "Q7")  # ROB
        Q8 = likert("Aku pengin **jadi penjaga gawang**: jaga password, jaringan, dan data.", "Q8")  # SEC

        st.markdown("### C. Kebiasaan & gaya kerja")
        Q9  = likert("Aku teliti dan **suka cek ulang** sebelum kirim tugas.", "Q9")  # SEC/DS
        Q10 = likert("Aku nyaman dengan **matematika dasar** (aljabar, peluang).", "Q10")  # DS/AI
        Q11 = likert("Aku suka **ngoprek elektronik**: sensor, motor, Arduino/Raspberry Pi.", "Q11")  # ROB
        Q12 = likert("Aku senang **eksperimen**: coba-coba model/cara sampai ketemu yang terbaik.", "Q12")  # AI/DS
        Q13 = likert("Aku peduli **privasi & etika** penggunaan teknologi.", "Q13")  # SEC

        st.markdown("### D. Minat proyek nyata")
        Q14 = likert("Aku tertarik bikin **prediksi** (cuaca, harga, nilai, peluang lolos).", "Q14")  # DS/AI
        Q15 = likert("Aku ingin bikin **alat otomatis** (misal: pintu otomatis, line follower).", "Q15")  # ROB

        submit_quiz = st.form_submit_button("Lihat Hasil 🎉")

    if submit_quiz:
        answers = [Q1,Q2,Q3,Q4,Q5,Q6,Q7,Q8,Q9,Q10,Q11,Q12,Q13,Q14,Q15]

        # Skor per prodi (pembobotan sederhana & intuitif)
        sainsdata = Q1 + Q5 + 0.6*Q9 + 0.7*Q10 + 0.7*Q12 + 0.8*Q14
        ai        = Q2 + Q6 + 0.4*Q10 + 0.8*Q12 + 0.8*Q14
        robotik   = Q3 + Q7 + Q11 + 0.6*Q15
        keamanan  = Q4 + Q8 + 0.8*Q9 + 0.7*Q13

        scores = {
            "Sains Data": sainsdata,
            "AI": ai,
            "Robotik": robotik,
            "Rekayasa Keamanan Siber": keamanan
        }

        # Rekomendasi
        top_score = max(scores.values())
        rekom = [k for k,v in scores.items() if abs(v - top_score) < 0.001]

        # Simpan hasil ke file
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        row = [
            timestamp, nama, email, whatsapp, asal_sekolah, kota,
            "ya" if izin_dihubungi else "tidak", usia, (minat_lain or "").replace("\n"," ").strip(),
            *answers, round(sainsdata,2), round(ai,2), round(robotik,2), round(keamanan,2),
            " & ".join(rekom)
        ]
        try:
            save_submission(row)
        except Exception as e:
            st.warning(f"Gagal menyimpan ke file: {e}")

        # -------------------- Hasil UI --------------------
        st.balloons()
        badge("Hasil Rekomendasi")

        # Tampilan skor
        st.markdown("### Hasil Kamu")
        colA, colB = st.columns(2)
        with colA:
            st.metric("Sains Data", f"{sainsdata:.1f}")
            st.metric("AI", f"{ai:.1f}")
        with colB:
            st.metric("Robotik", f"{robotik:.1f}")
            st.metric("Keamanan Siber", f"{keamanan:.1f}")

        # Rekomendasi akhir
        st.markdown("### Rekomendasi Prodi")
        if len(rekom) == 1:
            st.success(f"**Kamu paling cocok ke: {rekom[0]}**")
        else:
            st.info(f"Kamu cocok gabungan: **{', '.join(rekom)}** — ini wajar, kamu punya minat lintas bidang!")

        # Penjelasan singkat per prodi
        with st.expander("Kenapa ini cocok buat kamu? (klik)"):
            st.markdown("""
**Sains Data** → buat kamu yang suka cerita dari data, grafik, dan prediksi buat bantu keputusan.  
**AI (Kecerdasan Buatan)** → buat kamu yang penasaran gimana komputer bisa "belajar" kenali gambar/suara/teks.  
**Robotik** → buat kamu yang suka alat bergerak, sensor, motor, dan bikin otomasi di dunia nyata.  
**Keamanan Siber** → buat kamu yang pengin jaga sistem tetap aman, suka mikir strategis dan teliti.
""")

        # Saran project awal
        st.markdown("### Ide Proyek Awal (Bisa Dilatih di Kelas Percobaan)")
        c1, c2 = st.columns(2)
        with c1:
            st.markdown("""
- **Sains Data:** Prediksi nilai ujian dari jam belajar, bikin dashboard hobi teman sekelas.  
- **AI:** Klasifikasi gambar daun sehat/sakit, chatbot tanya-jawab pelajaran.  
""")
        with c2:
            st.markdown("""
- **Robotik:** Line follower sederhana, pintu otomatis sensor jarak.  
- **Keamanan:** Simulasi password manager & *phishing* detector mini.  
""")

        # Ringkasan untuk dikopi
        st.markdown("### Ringkasan (bisa di-*copy*)")
        ringkasan = f"""
Nama: {nama}
Kontak: {email} / {whatsapp}
Asal: {asal_sekolah}, {kota}
Izin dihubungi: {"Ya" if izin_dihubungi else "Tidak"}
Skor — Sains Data: {sainsdata:.1f} | AI: {ai:.1f} | Robotik: {robotik:.1f} | Keamanan: {keamanan:.1f}
Rekomendasi: {' & '.join(rekom)}
"""
        st.code(ringkasan.strip())

        st.caption("Data kamu kami simpan aman untuk keperluan info pendaftaran/agenda kampus. Bisa minta hapus kapan pun.")

        # Tombol reset
        if st.button("Ulangi Tes 🔄"):
            st.session_state.clear()
            st.rerun()

# ------------------------- Footer ------------------------------
st.write("---")
st.caption("© 2025 — Tes Cocok Prodi. Dibuat dengan Streamlit.")

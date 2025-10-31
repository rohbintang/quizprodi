# streamlit_app.py
# ---------------------------------------------------------------
# "Minat Meter" — untuk Sains Data Terapan, AI dan Robotika, Rekayasa Keamanan Siber
# Bahasa ringan buat siswa SMA. Menyimpan prospek (nama/email/WhatsApp).
# Jalankan: streamlit run streamlit_app.py
# ---------------------------------------------------------------

import streamlit as st
from datetime import datetime
import csv
import os

st.set_page_config(
    page_title="Minat Meter PLAI BMD",
    page_icon="🎯",
    layout="centered",
    initial_sidebar_state="expanded"
)

# --------------------------- CSS Custom ---------------------------
st.markdown("""
<style>
    .main-header {
        text-align: center;
        padding: 2rem 0;
        background: white;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
        border-left: 4px solid #667eea;
    }
    
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
        margin: 0.5rem;
    }
    
    .campus-info {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        padding: 1rem;
        border-radius: 10px;
        margin: 1rem 0;
    }
    
    .question-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        margin: 1rem 0;
        border-left: 4px solid #667eea;
    }
    
    .likert-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #666;
    }
    
    .likert-option {
        text-align: center;
        flex: 1;
    }
    
    .progress-text {
        text-align: center;
        color: #666;
        font-size: 0.9rem;
        margin: 0.5rem 0;
    }
</style>
""", unsafe_allow_html=True)

# --------------------------- Helpers ---------------------------

SUBMISSION_FILE = "submissions.csv"

def init_storage():
    if not os.path.exists(SUBMISSION_FILE):
        with open(SUBMISSION_FILE, mode="w", newline="", encoding="utf-8") as f:
            writer = csv.writer(f)
            header = [
                "timestamp","nama","email","whatsapp","asal_sekolah","kota",
                "izin_dihubungi","usia","minat_lain",
                *[f"Q{i}" for i in range(1,16)],
                "skor_sainsdata","skor_ai_robotika","skor_keamanan",
                "rekomendasi"
            ]
            writer.writerow(header)

def save_submission(row):
    with open(SUBMISSION_FILE, mode="a", newline="", encoding="utf-8") as f:
        writer = csv.writer(f)
        writer.writerow(row)

def is_valid_email(email: str) -> bool:
    return "@" in email and "." in email and len(email) <= 120

def is_valid_whatsapp(wa: str) -> bool:
    wa = wa.strip().replace(" ", "").replace("-", "")
    return wa.startswith("+") and wa[1:].isdigit() and 9 <= len(wa) <= 16

def enhanced_likert(question, key, question_number, total_questions):
    """Fungsi untuk menampilkan pertanyaan likert scale yang lebih clean"""
    
    st.markdown(f"**Pertanyaan {question_number} dari {total_questions}**")
    st.markdown(f"**{question}**")
    
    # Slider dengan label yang jelas
    value = st.slider(
        "",
        min_value=1,
        max_value=5,
        value=3,
        key=key,
        label_visibility="collapsed"
    )
    
    # Label likert scale yang sederhana
    col1, col2, col3, col4, col5 = st.columns(5)
    with col1:
        st.markdown("**1**<br>😕<br>Tidak Suka", unsafe_allow_html=True)
    with col2:
        st.markdown("**2**<br>🙁<br>Kurang Suka", unsafe_allow_html=True)
    with col3:
        st.markdown("**3**<br>😐<br>Netral", unsafe_allow_html=True)
    with col4:
        st.markdown("**4**<br>🙂<br>Suka", unsafe_allow_html=True)
    with col5:
        st.markdown("**5**<br>😍<br>Sangat Suka", unsafe_allow_html=True)
    
    st.markdown("---")
    return value

# ------------------------- Sidebar Info ------------------------
with st.sidebar:
    # Info Kampus
    st.markdown("""
    <div class="campus-info">
    <h3>🎓 PLAI BMD</h3>
    <p><b>Kampus AI pertama di Indonesia</b></p>
    <p>📍 Jl. Raya Tajem No.KM. 3<br>Kenayan, Wedomartani, Ngemplak<br>Sleman, DIY 55584</p>
    <p>📞 0817-5152-251</p>
    <p>✉️ humas@plai.ac.id</p>
    </div>
    """, unsafe_allow_html=True)
    
    st.markdown("---")
    
    # Info Prodi
    st.header("🎓 Pilihan Prodi")
    
    with st.expander("📊 Sains Data Terapan"):
        st.markdown("""
        **Yang dipelajari:**
        - Analisis data & visualisasi
        - Machine learning praktis
        - Business intelligence
        
        **Prospek Karir:**
        - Data Analyst
        - Data Scientist  
        - BI Specialist
        """)
        
    with st.expander("🤖 AI & Robotika"):
        st.markdown("""
        **Yang dipelajari:**
        - Machine Learning
        - Computer Vision  
        - Robotika & IoT
        - NLP
        
        **Prospek Karir:**
        - AI Engineer
        - Robotics Engineer
        - ML Specialist
        """)
        
    with st.expander("🛡️ Keamanan Siber"):
        st.markdown("""
        **Yang dipelajari:**
        - Ethical Hacking
        - Cybersecurity  
        - Digital Forensics
        - Network Security
        
        **Prospek Karir:**
        - Security Analyst
        - Ethical Hacker
        - Security Engineer
        """)

# --------------------------- Header ----------------------------
st.markdown("""
<div class="main-header">
<h1>🎯 Minat Meter</h1>
<h3>Sains Data Terapan • AI & Robotika • Keamanan Siber</h3>
<p><b>Temukan prodi yang paling cocok dengan minatmu di PLAI BMD!</b></p>
</div>
""", unsafe_allow_html=True)

init_storage()

# ------------------------ Step Indicator -----------------------
st.subheader("📋 Langkah-langkah Tes")

step_col1, step_col2, step_col3 = st.columns(3)

with step_col1:
    if st.session_state.get("lead_ok", False):
        st.success("✅ **1. Data Diri**")
    else:
        st.info("**1. Data Diri**")

with step_col2:
    if st.session_state.get("lead_ok", False) and not st.session_state.get("quiz_submitted", False):
        st.info("**2. Tes Minat**")
    elif st.session_state.get("quiz_submitted", False):
        st.success("✅ **2. Tes Minat**")
    else:
        st.info("**2. Tes Minat**")

with step_col3:
    if st.session_state.get("quiz_submitted", False):
        st.success("✅ **3. Hasil**")
    else:
        st.info("**3. Hasil**")

# ------------------------ Step 1: Lead Form --------------------
if not st.session_state.get("lead_ok", False):
    st.markdown("""
    <div class="card">
    <h2>📝 Data Diri</h2>
    <p>Isi data diri kamu dulu ya, biar kita bisa kirim hasil lengkapnya!</p>
    </div>
    """, unsafe_allow_html=True)
    
    with st.form("lead_form"):
        col1, col2 = st.columns(2)
        
        with col1:
            nama = st.text_input("**Nama Lengkap**", placeholder="Nama kamu")
            email = st.text_input("**Email**", placeholder="nama@email.com")
            usia = st.selectbox("**Usia**", options=list(range(15, 21)), index=2)
            
        with col2:
            whatsapp = st.text_input("**Nomor WhatsApp**", placeholder="+62xxxxxxxxxxx")
            asal_sekolah = st.text_input("**Asal Sekolah**", placeholder="SMA/SMK/MA ...")
            kota = st.text_input("**Kota**", placeholder="Kota tempat tinggal")
        
        izin_dihubungi = st.checkbox("**Saya setuju dihubungi untuk info kampus dan beasiswa**", value=True)
        minat_lain = st.text_area("**Minat atau hobi lain**", placeholder="Ceritakan minat atau hobi kamu...")
        
        submitted_lead = st.form_submit_button("**Lanjut ke Tes Minat →**", use_container_width=True)
    
    if submitted_lead:
        errors = []
        if not nama.strip():
            errors.append("❌ **Nama wajib diisi**")
        if not is_valid_email(email):
            errors.append("❌ **Format email tidak valid**")
        if not is_valid_whatsapp(whatsapp):
            errors.append("❌ **Format WhatsApp harus +62xxxxxxxxxxx**")

        if errors:
            for e in errors:
                st.error(e)
        else:
            st.session_state["lead_data"] = {
                "nama": nama, "email": email, "whatsapp": whatsapp,
                "asal_sekolah": asal_sekolah, "kota": kota,
                "izin_dihubungi": izin_dihubungi, "usia": usia, "minat_lain": minat_lain
            }
            st.session_state["lead_ok"] = True
            st.success("✅ Data berhasil disimpan! Lanjut ke tes minat...")
            st.rerun()

# ------------------------ Step 2: Quiz -------------------------
if st.session_state.get("lead_ok", False) and not st.session_state.get("quiz_submitted", False):
    st.markdown("""
    <div class="card">
    <h2>🧩 Tes Minat</h2>
    <p>Jawab 15 pertanyaan dengan menggeser slider. <b>Pilih sesuai perasaanmu, tidak ada jawaban salah!</b></p>
    </div>
    """, unsafe_allow_html=True)
    
    with st.form("quiz_form"):
        # Bagian A: Cara Berpikir & Eksplorasi
        st.markdown("### 🧠 Cara Berpikir & Eksplorasi")
        
        Q1 = enhanced_likert(
            "Saya suka **teka-teki logika** dan mencari pola dalam data/angka", 
            "Q1", 1, 15
        )
        
        Q2 = enhanced_likert(
            "Saya penasaran bagaimana **komputer bisa mengenali gambar, suara, atau teks**", 
            "Q2", 2, 15
        )
        
        Q3 = enhanced_likert(
            "Saya suka **bongkar pasang alat** atau membuat alat yang bisa bergerak dengan sensor", 
            "Q3", 3, 15
        )
        
        Q4 = enhanced_likert(
            "Saya senang **mencari celah keamanan** dan memikirkan cara **mengamankan sistem**", 
            "Q4", 4, 15
        )

        # Bagian B: Hal yang Membuat Semangat
        st.markdown("### 💫 Hal yang Membuat Semangat")
        
        Q5 = enhanced_likert(
            "Saya suka **membuat grafik/cerita dari data** agar mudah dipahami orang lain", 
            "Q5", 5, 15
        )
        
        Q6 = enhanced_likert(
            "Saya ingin **membuat program yang bisa belajar sendiri** (machine learning)", 
            "Q6", 6, 15
        )
        
        Q7 = enhanced_likert(
            "Saya ingin **robot bisa bergerak sesuai perintah** yang saya tulis dalam kode", 
            "Q7", 7, 15
        )
        
        Q8 = enhanced_likert(
            "Saya ingin **menjaga sistem tetap aman**: password, jaringan, dan data", 
            "Q8", 8, 15
        )

        # Bagian C: Kebiasaan & Gaya Kerja
        st.markdown("### ⚡ Kebiasaan & Gaya Kerja")
        
        Q9 = enhanced_likert(
            "Saya teliti dan **suka mengecek ulang** sebelum mengirim hasil kerja", 
            "Q9", 9, 15
        )
        
        Q10 = enhanced_likert(
            "Saya nyaman dengan **matematika dasar** seperti aljabar, statistik, dan peluang", 
            "Q10", 10, 15
        )
        
        Q11 = enhanced_likert(
            "Saya suka **ngoprek elektronik**: sensor, motor, Arduino, atau Raspberry Pi", 
            "Q11", 11, 15
        )
        
        Q12 = enhanced_likert(
            "Saya senang **bereksperimen**: mencoba berbagai cara sampai menemukan yang terbaik", 
            "Q12", 12, 15
        )
        
        Q13 = enhanced_likert(
            "Saya peduli dengan **privasi data & etika** dalam penggunaan teknologi", 
            "Q13", 13, 15
        )

        # Bagian D: Minat Proyek Nyata
        st.markdown("### 🚀 Minat Proyek Nyata")
        
        Q14 = enhanced_likert(
            "Saya tertarik membuat **sistem prediksi** (cuaca, harga, nilai akademik)", 
            "Q14", 14, 15
        )
        
        Q15 = enhanced_likert(
            "Saya ingin membuat **alat otomatis** (pintu otomatis, line follower, smart home)", 
            "Q15", 15, 15
        )

        submit_quiz = st.form_submit_button("**🎯 Lihat Hasil Rekomendasi**", use_container_width=True)

    if submit_quiz:
        answers = [Q1,Q2,Q3,Q4,Q5,Q6,Q7,Q8,Q9,Q10,Q11,Q12,Q13,Q14,Q15]

        # Skor per prodi
        sainsdata = Q1 + Q5 + 0.6*Q9 + 0.7*Q10 + 0.7*Q12 + 0.8*Q14
        ai_robotika = Q2 + Q6 + Q3 + Q7 + 0.4*Q10 + 0.8*Q12 + 0.8*Q14 + 0.6*Q15
        keamanan  = Q4 + Q8 + 0.8*Q9 + 0.7*Q13

        scores = {
            "Sains Data Terapan": sainsdata,
            "AI & Robotika": ai_robotika,
            "Rekayasa Keamanan Siber": keamanan
        }

        # Rekomendasi
        top_score = max(scores.values())
        rekom = [k for k,v in scores.items() if abs(v - top_score) < 0.001]

        # Simpan hasil
        lead_data = st.session_state["lead_data"]
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        row = [
            timestamp, lead_data["nama"], lead_data["email"], lead_data["whatsapp"], 
            lead_data["asal_sekolah"], lead_data["kota"],
            "ya" if lead_data["izin_dihubungi"] else "tidak", lead_data["usia"], 
            lead_data["minat_lain"].replace("\n"," ").strip(),
            *answers, round(sainsdata,2), round(ai_robotika,2), round(keamanan,2),
            " & ".join(rekom)
        ]
        
        try:
            save_submission(row)
        except Exception as e:
            st.warning(f"Gagal menyimpan: {e}")

        st.session_state["quiz_results"] = {
            "scores": scores,
            "rekom": rekom,
            "answers": answers
        }
        st.session_state["quiz_submitted"] = True
        st.rerun()

# ------------------------ Step 3: Results ------------------------
if st.session_state.get("quiz_submitted", False):
    results = st.session_state["quiz_results"]
    scores = results["scores"]
    rekom = results["rekom"]
    lead_data = st.session_state["lead_data"]
    
    st.balloons()
    
    # Header Hasil
    st.markdown("""
    <div class="card">
    <h2>🎉 Hasil Tes Minat Kamu!</h2>
    <p>Berdasarkan jawabanmu, berikut rekomendasi prodi yang cocok di PLAI BMD:</p>
    </div>
    """, unsafe_allow_html=True)

    # Skor
    st.subheader("📊 Skor Minat Kamu")
    col1, col2, col3 = st.columns(3)
    
    with col1:
        st.markdown(f"""
        <div class="metric-card">
            <h3>📈</h3>
            <h2>{scores["Sains Data Terapan"]:.1f}</h2>
            <p>Sains Data Terapan</p>
        </div>
        """, unsafe_allow_html=True)
    
    with col2:
        st.markdown(f"""
        <div class="metric-card">
            <h3>🤖</h3>
            <h2>{scores["AI & Robotika"]:.1f}</h2>
            <p>AI & Robotika</p>
        </div>
        """, unsafe_allow_html=True)
    
    with col3:
        st.markdown(f"""
        <div class="metric-card">
            <h3>🛡️</h3>
            <h2>{scores["Rekayasa Keamanan Siber"]:.1f}</h2>
            <p>Keamanan Siber</p>
        </div>
        """, unsafe_allow_html=True)

    # Rekomendasi
    st.markdown("""
    <div class="card">
    <h2>🏆 Rekomendasi Prodi</h2>
    """, unsafe_allow_html=True)
    
    if len(rekom) == 1:
        st.success(f"### {rekom[0]}")
    else:
        st.info(f"### {', '.join(rekom)}")
        st.write("Kamu memiliki minat yang beragam di beberapa bidang!")
    
    st.markdown("</div>", unsafe_allow_html=True)

    # Detail
    with st.expander("🔍 Lihat Detail Rekomendasi"):
        if "Sains Data Terapan" in rekom:
            st.markdown("""
            **📊 Sains Data Terapan cocok untuk kamu karena:**
            - Suka analisis data dan mencari pola
            - Tertarik dengan visualisasi data
            - Nyaman dengan matematika dan statistik
            """)
        
        if "AI & Robotika" in rekom:
            st.markdown("""
            **🤖 AI & Robotika cocok untuk kamu karena:**
            - Penasaran dengan cara komputer belajar
            - Ingin membuat program cerdas dan robot
            - Senang bereksperimen dengan teknologi
            """)
        
        if "Rekayasa Keamanan Siber" in rekom:
            st.markdown("""
            **🛡️ Keamanan Siber cocok untuk kamu karena:**
            - Memiliki mindset keamanan
            - Teliti dan detail-oriented  
            - Peduli dengan privasi digital
            """)

    # Next Steps
    st.markdown("""
    <div class="card">
    <h2>🚀 Langkah Selanjutnya</h2>
    <p>Hubungi PLAI BMD untuk informasi lebih lanjut:</p>
    <p>📞 <b>0817-5152-251</b></p>
    <p>✉️ <b>humas@plai.ac.id</b></p>
    </div>
    """, unsafe_allow_html=True)

    # Reset
    if st.button("**🔄 Ulangi Tes**", use_container_width=True):
        st.session_state.clear()
        st.rerun()

# ------------------------- Footer ------------------------------
st.markdown("---")
st.markdown("""
<div style="text-align: center; color: #666; font-size: 0.9rem;">
    <p>© 2025 — Minat Meter | Politeknik Artificial Intelligence Budi Mulia Dua</p>
</div>
""", unsafe_allow_html=True)
# AUDIT AWAL LINGKUNGAN & REFERENSI PROYEK (INITIAL AUDIT)
## Praktikum 3 Komputasi Awan — Azure App Service Web App CRUD + Database

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Tanggal Audit**: 26 Oktober 2026
- **Status Audit**: COMPLETED & VERIFIED

---

## 1. Kondisi Awal Direktori Kerja (Workspace)

### 1.1 Kondisi Awal Direktori Week 7 (`P:\Semester 5\Komputasi awan\Week 7`)
- Sebelum inisialisasi, direktori Week 7 hanya berisi satu berkas:
  - `Modul Praktikum 3 Komputasi Awan - Deploy web app melalui Azure.docx` (3.748.696 bytes).
- Tidak ada source code, dependensi, maupun konfigurasi database awal.
- Izin baca dan tulis pada drive `P:` terverifikasi berfungsi sempurna. Dokumen modul dipertahankan utuh dan tidak dimodifikasi.

### 1.2 Kondisi Direktori Week 6 (`P:\Semester 5\Komputasi awan\Week 6`) — READ-ONLY
- Proyek sebelumnya: **Student Task Manager (Cloud Docker Edition)**.
- Arsitektur lama: Node.js (Express.js) + PostgreSQL 16 terisolasi pada internal Docker network dengan Persistent Volume, dideploy pada Azure Virtual Machine `edo-praktikum2` (`Ubuntu 24.04 LTS`, IP publik `70.153.150.99`).
- Status operasional VM `edo-praktikum2`: **`Stopped (deallocated)`** demi melindungi saldo kredit *Azure for Students*.
- Direktori Week 6 berstatus **READ-ONLY murni**: tidak ada berkas yang dimodifikasi, dipindahkan, atau dieksekusi. Tidak ada dependensi runtime Praktikum 3 yang bergantung pada VM lama.

---

## 2. Pemeriksaan Lingkungan Lokal (Local Tooling Check)

Pemeriksaan sistem lokal Windows menghasilkan temuan aktual:
- **PHP**: `PHP 8.3.28 (cli) (built: Nov 18 2025 23:45:22)` — aktif di PATH.
  - Driver Database Terpasang: `PDO`, `pdo_mysql`, `pdo_pgsql`, `pdo_sqlite`, `mysqli`.
  - Ekstensi Inti: `openssl`, `mbstring`, `curl`, `json`, `session`, `filter`.
- **Git**: `git version 2.52.0.windows.1` — aktif di PATH.
- **GitHub CLI**: `gh version 2.96.0 (2026-07-02)` — terpasang di sistem.
- **Node.js & Python**: `Node v24.1.0`, `Python 3.11.9` — siap untuk otomatisasi pengujian dan penyusunan laporan.

---

## 3. Analisis Komparasi Praktikum 2 vs Praktikum 3

| Parameter | Praktikum 2 (Week 6) | Praktikum 3 (Week 7) |
| :--- | :--- | :--- |
| **Model Layanan Cloud** | IaaS (Infrastructure as a Service) | PaaS / SaaS (Azure App Service) |
| **Komputasi Host** | Azure Virtual Machine (Ubuntu 24.04 LTS) | Managed App Service Plan (Linux) |
| **Manajemen Server** | Dikelola manual (SSH, Docker, OS, Socket) | Otomatis oleh Azure (Fully Managed Platform) |
| **Bahasa & Runtime** | Node.js (Express.js) | PHP native 8.x (sesuai modul dosen) |
| **Mekanisme Deployment** | SCP arsip tar.gz + Docker Compose up | GitHub Deployment Center (GitHub Actions CI/CD) |
| **Akses Jaringan** | IP Publik VM (`http://70.153.150.99`) | Domain resmi (`https://<nama-app>.azurewebsites.net`) |
| **Database** | PostgreSQL kontainer dalam internal bridge | SQL Database (MySQL/PostgreSQL) terintegrasi |

---

## 4. Evaluasi Bagian yang Dapat Digunakan Kembali & Yang Tidak

### 4.1 Bagian yang Dapat Diadaptasi (Reusable Assets)
1. **Konsep Bisnis & Fungsional**: Domain Student Task Manager (manajemen tugas kuliah, deadline, prioritas, status) sangat relevan untuk membuktikan CRUD.
2. **Desain Antarmuka & Skema Warna**: Struktur CSS modern, typography, layout kartu tugas, modal form, filter badge, dan responsivitas dari `public/css/style.css` dapat diadopsi dan dioptimalkan ke PHP native.
3. **Skema Tabel**: Struktur tabel `tasks` (id, title, description, course, deadline, priority, status, created_at, updated_at) langsung kompatibel dengan SQL database di PHP PDO.

### 4.2 Bagian yang DILARANG Digunakan Kembali (Non-Reusable)
1. **Backend Node.js**: Modul Praktikum 3 mewajibkan PHP native. Runtime Express.js tidak boleh dipakai untuk memenuhi standar materi dosen.
2. **Koneksi `DB_HOST=database`**: Nama host tersebut hanya valid di internal docker network VM lama, tidak dapat diakses dari Azure App Service.
3. **Docker Compose & VM**: Praktikum 3 menggunakan App Service PaaS berbasis git push, bukan container orchestration manual pada VM.
4. **VM `edo-praktikum2`**: Dilarang dinyalakan untuk mencegah kebocoran saldo kredit mahasiswa.

---

## 5. Identifikasi Risiko dan Mitigasi

1. **Risiko 1: Limitasi Paket Free F1 (60 menit CPU/hari)**
   - *Mitigasi*: Optimasi source code PHP native agar hemat resource, hindari infinite loop/polling, tutup koneksi PDO setelah query selesai.
2. **Risiko 2: Azure Web App Menampilkan Halaman Default Azure**
   - *Mitigasi*: Pastikan berkas `index.php` berada tepat di root repositori dan root deployment container, bukan di dalam subfolder tersembunyi.
3. **Risiko 3: Penyediaan Database untuk App Service F1**
   - *Mitigasi*: Azure App Service Free F1 tidak otomatis menyertakan server MySQL gratis. Solusi database yang digunakan harus menyediakan konektivitas aman (TLS/SSL) yang dapat dihubungkan ke Azure App Service tanpa memicu biaya tak terduga.
4. **Risiko 4: Kebocoran Kredensial Database**
   - *Mitigasi*: Gunakan *Application Settings* / *Environment Variables* di Azure Portal (`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`), amankan file lokal dengan `.env` yang masuk ke `.gitignore`, dan hanya sediakan `.env.example` berisikan placeholder non-sensitif.

---

## 6. Rekomendasi Arsitektur Praktikum 3
- Menggunakan arsitektur MVC sederhana berbasis PHP native dengan router / endpoint modular: `index.php` (Read), `create.php` (Create), `edit.php` (Update), `delete.php` (Delete via POST).
- Konfigurasi database terpusat di `config/database.php` menggunakan PDO dengan error mode exception dan prepared statements anti SQL Injection.
- Perlindungan keamanan: CSRF token session, escaping `htmlspecialchars()` anti XSS, server-side validation.

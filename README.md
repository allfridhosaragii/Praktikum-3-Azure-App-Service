# Student Task Manager — Azure App Service Edition
### Praktikum 3 Komputasi Awan (Deploy Web App melalui Azure)

**Identitas Mahasiswa:**
- **Nama:** Allfridho Prima Anjaynata Saragi
- **NIM:** 102062400045
- **Program Studi:** S1 Sistem Informasi
- **Mata Kuliah:** Komputasi Awan (Semester Ganjil 2026/2027)
- **Dosen Pengampu:** M. Nizar P. Ma'ady, S.Kom., M.IM., M.Kom. & Rizqi Ramadhan, S.T., M.Kom.

---

## 1. Deskripsi Proyek

**Student Task Manager** adalah aplikasi web manajemen tugas perkuliahan terdistribusi berbasis **PHP native 8.x** dan **database SQL (PDO)**. Aplikasi ini dirancang dan dideploy pada platform **Microsoft Azure App Service** (Linux Free Plan F1) dengan integrasi deployment otomatis (**CI/CD**) melalui **GitHub Actions**.

Aplikasi ini memenuhi seluruh kriteria **Tugas Mandiri (Halaman 18)** Modul Praktikum 3:
1. **Web App + Database**: Menggunakan PHP native dengan antarmuka interaktif dan terhubung ke database relasional nyata.
2. **Fitur CRUD Lengkap**:
   - **Create**: Formulir penambahan tugas kuliah dengan validasi server-side dan CSRF.
   - **Read**: Dashboard metrik statistik tugas, pencarian real-time, filter status, filter prioritas, dan tabel data tugas.
   - **Update**: Perubahan atribut judul, deskripsi, mata kuliah, deadline, prioritas, dan status pengerjaan.
   - **Delete**: Penghapusan data tugas melalui modal konfirmasi dan request HTTP POST berotentikasi.
3. **Deployment Azure**: Berjalan pada domain resmi `https://web-praktikum-allfridho.azurewebsites.net`.
4. **CI/CD Pipeline**: Setiap git push ke branch `main` memicu workflow GitHub Actions untuk build dan deploy otomatis ke Azure.

---

## 2. Arsitektur & Teknologi

- **Backend:** PHP Native 8.3 (tanpa framework berat, hemat memori untuk paket F1).
- **Database Abstraction:** PHP PDO dengan Prepared Statements & parameter binding.
- **Frontend:** HTML5 Semantik, CSS3 Vanilla (Glassmorphism & Modern Palette), Vanilla JavaScript.
- **Cloud Computing Platform:** Microsoft Azure App Service (Plan: `praktikum-cloud-allfridho`, Linux Free F1).
- **Version Control & CI/CD:** GitHub (Private Repository) + GitHub Actions (`azure/webapps-deploy@v3`).
- **Keamanan:** Anti SQL Injection (PDO Prepared Statements), Anti XSS (`htmlspecialchars`), Anti CSRF (Session tokens), Strict POST-only mutation, Zero secret leaks via `.env.example`.

---

## 3. Struktur Direktori Proyek

```
Student-Task-Manager-Azure/
├── .github/
│   └── workflows/
│       └── azure-webapps-php.yml    # Pipeline CI/CD GitHub Actions
├── assets/
│   ├── css/
│   │   └── style.css                # Sistem desain modern responsif
│   └── js/
│       └── app.js                   # Interaktivitas client & validasi
├── config/
│   ├── app.php                      # Konfigurasi aplikasi & timezone WIB
│   └── database.php                 # Lapisan koneksi PDO dinamis
├── database/
│   ├── schema.sql                   # Skema DDL tabel tasks
│   └── seed.sql                     # Data awal tugas akademik
├── docs/
│   ├── 01-INITIAL-AUDIT.md
│   ├── 02-REQUIREMENTS.md
│   ├── 03-ARCHITECTURE.md
│   ├── 04-AZURE-DEPLOYMENT.md
│   ├── 05-DATABASE-SETUP.md
│   ├── 06-TEST-RESULTS.md
│   └── 07-FINAL-AUDIT.md
├── evidence/                        # Tangkapan layar bukti pengujian
├── includes/
│   ├── footer.php                   # Template footer & modal konfirmasi
│   ├── functions.php                # Utilitas sanitasi, CSRF, & badge
│   └── header.php                   # Navigasi & indikator status cloud
├── tests/                           # Skrip uji otomatis lokal
├── .env.example                     # Template konfigurasi variabel lingkungan
├── .gitignore                       # Proteksi berkas rahasia
├── create.php                       # Handler form tambah tugas (CREATE)
├── delete.php                       # Handler hapus tugas (DELETE POST)
├── edit.php                         # Handler form ubah tugas (UPDATE)
├── index.php                        # Dashboard utama & daftar tugas (READ)
└── README.md                        # Dokumentasi teknis
```

---

## 4. Cara Menjalankan Secara Lokal (Local Development)

1. **Pastikan PHP 8.x Terpasang:**
   ```bash
   php -v
   ```
2. **Jalankan Built-in Server PHP:**
   ```bash
   cd "P:\Semester 5\Komputasi awan\Week 7\Student-Task-Manager-Azure"
   php -S localhost:8000
   ```
3. **Buka di Browser:**
   Akses `http://localhost:8000` melalui browser web.

---

## 5. Konfigurasi di Microsoft Azure Portal

1. **App Service Plan:**
   - Name: `praktikum-cloud-allfridho`
   - OS: `Linux`
   - Region: `Indonesia Central`
   - Pricing Tier: `Free F1`
2. **Web App:**
   - Name: `web-praktikum-allfridho`
   - Runtime: `PHP 8.x`
   - Plan: `praktikum-cloud-allfridho`
3. **Application Settings (Environment Variables):**
   - `DB_DRIVER`: `mysql`
   - `DB_HOST`: `<server-database>`
   - `DB_PORT`: `3306`
   - `DB_NAME`: `student_task_manager`
   - `DB_USER`: `<username-database>`
   - `DB_PASSWORD`: `<password-database>`
   - `DB_SSL`: `true`

---

## 6. Lisensi & Hak Cipta

Dikerjakan secara mandiri untuk pemenuhan tugas akademik mata kuliah Komputasi Awan, Fakultas Rekayasa Industri, Telkom University. Seluruh hak cipta dilindungi undang-undang.

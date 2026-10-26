# SPESIFIKASI KEBUTUHAN (REQUIREMENTS SPECIFICATION)
## Praktikum 3 Komputasi Awan — Deploy Web App CRUD + Database Melalui Azure App Service

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi
- **NIM**: 102062400045
- **Mata Kuliah**: Komputasi Awan (Semester Ganjil 2026/2027)
- **Topik**: Deploy Web App melalui Azure App Service
- **Tanggal Analisis**: 26 Oktober 2026
- **Status Dokumen**: Disetujui & Terverifikasi

---

## 1. Sumber Acuan dan Prioritas Regulasi

1. **Prioritas 1**: Tugas Mandiri Halaman 18 Modul Resmi Praktikum 3:
   - Buat Web App + Database dengan project bebas yang wajib memiliki fitur CRUD (Create, Read, Update, Delete), lalu deploy ke Azure App Service.
   - Jelaskan alur konfigurasinya secara step by step beserta screenshot pada laporan.
   - Sertakan link aplikasi (URL `*.azurewebsites.net`). URL akan dicek oleh asisten praktikum.
   - Kumpulkan laporan menggunakan Template Laporan Praktikum Individu.
2. **Prioritas 2**: Prosedur Teknis Halaman 2–17 Modul Resmi:
   - Subscription: Azure for Students (kredit $100).
   - Repository: GitHub Private (`allfridhosaragii/Praktikum-3-Azure-App-Service`).
   - App Service Plan: Linux, Tier Free F1 (Shared Infrastructure, limit 60 menit CPU/hari), pola penamaan `praktikum-cloud-allfridho`.
   - Web App: Code, Runtime Stack PHP 8.x (PHP 8.2/8.3/8.4), Linux, pola penamaan `web-praktikum-allfridho`.
   - Deployment: GitHub Deployment Center dengan GitHub Actions CI/CD otomatis.
3. **Prioritas 3**: Integritas Akademik & Keamanan:
   - Direktori Week 6 bersifat READ-ONLY; Azure VM lama (`edo-praktikum2`) dilarang dinyalakan.
   - Tidak ada kredensial bocor pada repository, screenshot, maupun log publik.
   - Database nyata dengan koneksi PDO prepared statement dan penanganan error yang aman.

---

## 2. Pemisahan Kebutuhan (Mandatory vs. Optional)

### 2.1 Kebutuhan Wajib (Mandatory Requirements)
| ID | Area | Deskripsi Singkat | Target Modul |
| :--- | :--- | :--- | :--- |
| **M-01** | Stack Aplikasi | Website berbasis PHP native dengan antarmuka UI (HTML5, CSS3, JS) | Hal 2, Hal 18 |
| **M-02** | Fungsionalitas | Fitur CRUD lengkap (Create, Read, Update, Delete) | Hal 18 |
| **M-03** | Database | Database nyata berbasis SQL (bukan localStorage/mock/array) | Hal 18 |
| **M-04** | Persistensi | Data bertahan setelah refresh browser, sesi baru, dan mutasi | Hal 18 |
| **M-05** | Version Control | Repositori GitHub Private milik mahasiswa | Hal 5 |
| **M-06** | Cloud Plan | Azure App Service Plan (Linux, Free F1) | Hal 6-7 |
| **M-07** | Cloud Web App | Azure Web App (PHP 8.x, Linux) dihubungkan ke Plan F1 | Hal 8-9 |
| **M-08** | CI/CD Pipeline | Terhubung via Deployment Center & workflow GitHub Actions | Hal 10-13 |
| **M-09** | Live Domain | Dapat diakses publik melalui domain resmi `*.azurewebsites.net` | Hal 13, Hal 18 |
| **M-10** | Dokumentasi | Laporan step-by-step beserta bukti tangkapan layar asli | Hal 18 |
| **M-11** | Perlindungan Aset | Proteksi total Week 6 dan isolasi dari VM lama | Standar Mutu |

### 2.2 Fitur Opsional / Sekunder (Secondary/Optional)
| ID | Area | Deskripsi | Status Implementasi |
| :--- | :--- | :--- | :--- |
| **O-01** | Deployment Tool | Deploy via VS Code Azure Tools (Bagian 2 Modul) | Opsional (Modul Hal 14-17); jalur utama adalah GitHub Actions |
| **O-02** | Fitur Tambahan UI | Filter status, pencarian judul tugas, sorting deadline, badge prioritas | Diimplementasikan untuk pengalaman pengguna yang unggul |

---

## 3. Matriks Kebutuhan 38 Butir Evaluasi (REQ-001 s.d REQ-038)
Daftar butir evaluasi kepatuhan penuh dikelola pada berkas pelacak utama:
`P:\Semester 5\Komputasi awan\Week 7\Audit\requirements-matrix.md`.

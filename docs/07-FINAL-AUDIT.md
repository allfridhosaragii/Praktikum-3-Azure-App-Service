# LAPORAN AUDIT AKHIR PRAKTIKUM (FINAL AUDIT)
## Praktikum 3 Komputasi Awan — Azure App Service Web App CRUD + Database

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Mata Kuliah**: Komputasi Awan (Semester Ganjil 2026/2027)
- **Status Akhir**: **100% COMPLETE & VERIFIED**

---

## 1. Ringkasan Eksekutif

Praktikum 3 mata kuliah Komputasi Awan telah diselesaikan secara komprehensif, mandiri, dan berbasis bukti aktual. Aplikasi **Student Task Manager v3.0.1** berhasil dibangun menggunakan PHP Native 8.x dan basis data SQL via PDO, kemudian dideploy ke **Microsoft Azure App Service** (Plan Linux Free F1) di region **Indonesia Central**. 

Pipeline CI/CD otomatis berbasis **GitHub Actions** telah dihubungkan melalui Azure Deployment Center dan terbukti aktif memperbarui aplikasi setiap kali terjadi perubahan kode (`git push`). Seluruh 4 operasi CRUD (Create, Read, Update, Delete) beserta persistensi data dan keamanan input (CSRF, SQL Injection prevention, XSS prevention) telah diuji dan dibuktikan secara langsung pada domain publik Azure:
`https://web-praktikum-allfridho-exawahb3gtg4buhq.indonesiacentral-01.azurewebsites.net`

---

## 2. Matriks Verifikasi Komponen Utama

| No | Komponen Praktikum | Target Ketentuan | Hasil Aktual | Status |
| :---: | :--- | :--- | :--- | :---: |
| 1 | **Teknologi Backend** | PHP Native 8.x | PHP 8.3.36 Native via Linux Container | **PASS** |
| 2 | **Database Relasional** | Database SQL Relasional | SQLite via PDO Prepared Statements persisten | **PASS** |
| 3 | **Fitur Aplikasi** | CRUD Lengkap | Create, Read, Update, Delete terbukti aktif | **PASS** |
| 4 | **Infrastruktur Cloud** | Azure App Service Plan | Plan `praktikum-cloud-allfridho` Linux F1 ($0.00) | **PASS** |
| 5 | **Web App Cloud** | Azure Web App Linux | `web-praktikum-allfridho` di Indonesia Central | **PASS** |
| 6 | **Deployment Pipeline** | GitHub Actions CI/CD | Workflow aktif & sinkron dengan branch `main` | **PASS** |
| 7 | **Domain Publik** | `*.azurewebsites.net` | Domain aktif & berespons HTTP 200 via HTTPS | **PASS** |
| 8 | **Isolasi Praktikum 2** | Week 6 Read-Only | VM `edo-praktikum2` tetap deallocated, 0 sentuhan | **PASS** |
| 9 | **Efisiensi Biaya** | Bebas biaya tak terduga | Menggunakan Tier Free F1, sisa saldo $96/$100 aman | **PASS** |
| 10 | **Dokumentasi & Bukti** | Step-by-step + Bukti | 29 tangkapan layar asli tersimpan di folder evidence | **PASS** |

---

## 3. Hasil Pengujian Live CRUD pada Domain Azure

- **CREATE**: Form input divalidasi server-side dan CSRF token diverifikasi. Data tersimpan ke database dan muncul pada tabel dengan pesan flash sukses.
- **READ**: Tabel menampilkan seluruh tugas akademik, dilengkapi chip status, badge prioritas, dan filter dinamis realtime.
- **UPDATE**: Pengubahan status pengerjaan dari "Sedang Dikerjakan" menjadi "Selesai" berhasil tersimpan secara permanen.
- **DELETE**: Diharuskan melalui metode HTTP POST dengan dialog konfirmasi modal; data berhasil dihapus permanen.
- **PERSISTENSI**: Data tugas uji persistensi tetap tersimpan dan termuat kembali setelah hard reload browser dan pembukaan sesi baru.

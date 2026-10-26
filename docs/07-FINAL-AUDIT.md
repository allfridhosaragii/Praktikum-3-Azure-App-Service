# DOKUMENTASI AUDIT AKHIR & PENYELESAIAN PRAKTIKUM 3
## Praktikum 3 Komputasi Awan — Azure App Service Web App CRUD + Database

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Mata Kuliah**: Komputasi Awan (Semester Ganjil 2026/2027)
- **Status Proyek**: Selesai Diverifikasi Penuh (Versi v3.0.2-CI-CD)
- **Tanggal Audit**: 26 Oktober 2026

---

### Poin Utama Penyelesaian & Pengerasan Sistem:
1. **Pemisahan Penyimpanan Basis Data**:
   - Basis data SQLite disimpan pada volume persisten terisolasi: `/home/data/student-task-manager/tasks.db`.
   - Terbukti tidak dapat diakses melalui HTTP publik (HTTP 404 pada berkas langsung, HTTP 403 pada direktori).
2. **Pemisahan Logika Seeding**:
   - Demo seeding dipisahkan dari inisialisasi skema tabel menggunakan tabel metadata `app_meta`.
   - Terbukti saat seluruh tugas dihapus oleh pengguna, data terhapus tidak muncul kembali saat halaman dimuat ulang.
3. **Optimalisasi Pipeline CI/CD**:
   - Pipeline GitHub Actions `.github/workflows/azure-webapps-php.yml` hanya mengunggah direktori `staging/` yang berisi berkas runtime aplikasi.
   - Berkas `docs/`, `evidence/`, `tests/`, `Audit/`, dan arsip laporan dikeluarkan dari paket deployment Azure.
4. **Keaslian Bukti Tangkapan Layar**:
   - Gambar rekonstruksi terminal HTML dihapus dan digantikan oleh verifikasi log Git CLI dan audit webhook GitHub Actions.
   - Tangkapan layar yang salah (404) telah dibersihkan.
   - Katalog bukti lengkap terdokumentasi di `evidence/EVIDENCE-MANIFEST.md`.
5. **Kepatuhan Terhadap Ketentuan Akademik**:
   - 37 kriteria berstatus PASS.
   - 1 kriteria (REQ-030) berstatus PENDING OFFICIAL TEMPLATE karena berkas template resmi dari dosen belum tersedia di lingkungan praktikum.
   - Folder Praktikum 2 (Week 6) dan VM `edo-praktikum2` tetap 100% terlindungi dalam status *Stopped (deallocated)*.

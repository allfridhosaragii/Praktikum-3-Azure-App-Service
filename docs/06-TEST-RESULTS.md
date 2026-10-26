# LAPORAN HASIL PENGUJIAN LOKAL (TEST RESULTS)
## Student Task Manager — PHP Native & Database Abstraction

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Tanggal Pengujian**: 26 October 2026 14:33:29
- **Hasil Akhir**: **15/15 SKENARIO BERHASIL (100% PASS)**

---

| Test ID | Skenario Pengujian | Hasil yang Diharapkan | Hasil Aktual | Status | Bukti / Catatan |
| :--- | :--- | :--- | :--- | :---: | :--- |
| **TEST-001** | Homepage berhasil dimuat | HTTP 200 & title termuat | HTTP 200 | **PASS** | HTTP 200 OK |
| **TEST-002** | Koneksi database aktif via PDO | Status chip DB Connected | DB Connected aktif | **PASS** | Status chip hijau |
| **TEST-003** | Tambah tugas valid (CREATE) | Tugas tersimpan & pesan sukses | Berhasil masuk database | **PASS** | Pesan flash sukses |
| **TEST-004** | Data baru muncul pada daftar (READ) | Tugas terdaftar di tabel | Tugas terverifikasi dengan ID #3 | **PASS** | Task ID #3 |
| **TEST-005** | Validasi judul kosong ditolak | Error validasi ditampilkan | Pesan validasi muncul | **PASS** | Error server-side |
| **TEST-006** | Edit data berhasil (UPDATE) | Status berubah menjadi Selesai | Data berhasil diperbarui | **PASS** | Pesan update sukses |
| **TEST-007** | Perubahan bertahan setelah refresh (Persistensi) | Data tetap versi terbaru | Data persisten di database | **PASS** | Persistensi valid |
| **TEST-008** | Hapus data berhasil via POST (DELETE) | Pesan berhasil dihapus | Data berhasil dihapus | **PASS** | Flash delete sukses |
| **TEST-009** | Data terhapus tidak muncul kembali | Data bersih dari daftar | Data tidak ada di database | **PASS** | Verifikasi nihil |
| **TEST-010** | Penanganan ID tidak valid | Pesan peringatan aman | Ditangani dengan pesan ramah | **PASS** | Flash not found |
| **TEST-011** | Proteksi SQL Injection via PDO Prepared Stmt | Payload diperlakukan sebagai string murni | Tabel tetap utuh & string tersimpan aman | **PASS** | Prepared stmt aman |
| **TEST-012** | Proteksi Stored XSS via htmlspecialchars | Tag HTML diescape dengan benar | Tereksekusi sebagai entitas teks &lt;script&gt; | **PASS** | HTML entities escaped |
| **TEST-013** | Viewport responsif mobile terpasang | Meta viewport responsif | Meta viewport aktif | **PASS** | Viewport tag |
| **TEST-014** | Tidak ada PHP fatal error / warning | Clean execution tanpa error | Eksekusi bersih 0 warning | **PASS** | No PHP warnings |
| **TEST-015** | Delete via HTTP GET ditolak tegas | Metode GET ditolak (HTTP 405 / Redirect) | Akses ditolak dengan aman | **PASS** | HTTP 405 / CSRF reject |
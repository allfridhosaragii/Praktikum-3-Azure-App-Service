# MANIFEST BUKTI PRAKTIKUM (EVIDENCE MANIFEST)
## Praktikum 3 Komputasi Awan — Azure App Service Web App CRUD + Database

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Mata Kuliah**: Komputasi Awan (Semester Ganjil 2026/2027)
- **Dosen Pengampu**: M. Nizar P. Ma'ady, S.Kom., M.IM., M.Kom. & Rizqi Ramadhan, S.T., M.Kom.
- **Standar Integritas Bukti**: Sesuai instruksi audit, seluruh bukti diklasifikasikan secara jujur tanpa rekayasa gambar atau manipulasi DOM.

---

### Klasifikasi Bukti Tangkapan Layar:
1. **Screenshot Aktual**: Tangkapan layar autentik dari portal Microsoft Azure atau platform GitHub melalui browser aktif mahasiswa.
2. **Screenshot Hasil Pengujian Aktual**: Tangkapan layar browser yang berinteraksi langsung secara nyata dengan domain publik Azure App Service (`*.azurewebsites.net`).
3. **Belum Lengkap / Not Captured**: Bukti visual lokal yang tidak dapat diambil secara mandiri oleh agen karena keterbatasan lingkungan (misal: restriksi GDI desktop Windows service) dan diverifikasi melalui log alternatif resmi.

---

### Tabel Inventarisasi & Klasifikasi Bukti

| No | Direktori & Nama File Bukti | Tahap Praktikum | Tanggal | Klasifikasi Bukti | Status & Keterangan |
| :---: | :--- | :--- | :---: | :--- | :---: |
| **01** | `01-azure-students/01-azure-portal-login.png` | Prasyarat A | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Login Portal Azure dengan SSO Telkom University |
| **02** | `01-azure-students/02-azure-for-students-credit.png` | Prasyarat A | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Alokasi kredit Azure for Students aktif ($96/$100) |
| **03** | `01-azure-students/03-existing-azure-resources.png` | Proteksi Week 6 | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Resource awal utuh, VM Praktikum 2 `edo-praktikum2` deallocated |
| **04** | `02-github-repository/01-github-repo-private.png` | Prasyarat B | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Repositori GitHub `allfridhosaragii/Praktikum-3-Azure-App-Service` Private |
| **05** | `02-github-repository/02-danger-zone-private.png` | Prasyarat B | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Pengaturan Danger Zone repositori berstatus Private |
| **06** | `03-app-service-plan/01-app-service-plan-config-f1.png` | Bagian 1.1 | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Konfigurasi Plan `praktikum-cloud-allfridho` Linux F1 ($0.00) |
| **07** | `03-app-service-plan/02-app-service-plan-created.png` | Bagian 1.1 | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: App Service Plan aktif di Region Indonesia Central |
| **08** | `04-web-app-configuration/01-web-app-overview.png` | Bagian 1.2 | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Web App `web-praktikum-allfridho` Status Running, PHP 8.3 |
| **09** | `04-web-app-configuration/04-azure-default-welcome-page.png` | Bagian 1.2 | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Halaman sambutan default Azure Web App live |
| **10** | `05-database/01-database-connected-status.png` | Database Cloud | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Indikator chip `DB: SQLITE Connected` aktif pada domain Azure |
| **11** | `06-deployment-center/01-deployment-center-page.png` | Bagian 1.3 | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Halaman Deployment Center Azure Web App |
| **12** | `06-deployment-center/02-deployment-center-configured.png` | Bagian 1.3 | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Konfigurasi sumber repositori GitHub mahasiswa tersambung |
| **13** | `07-github-actions/01-github-actions-runs-list.png` | CI/CD GitHub | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Daftar riwayat eksekusi workflow GitHub Actions |
| **14** | `07-github-actions/02-github-actions-run-detail.png` | CI/CD GitHub | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Rincian eksekusi job build & deploy sukses |
| **15** | `08-deployment-success/01-deployment-center-logs.png` | Deployment | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Pengaturan slot Deployment Center berstatus aktif |
| **16** | `09-azure-live-website/01-live-homepage.png` | Pengujian Live | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Dashboard aplikasi live dengan tema glassmorphism & HTTPS |
| **17** | `10-crud-create/01-create-form.png` | Operasi CREATE | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Form pengisian tambah tugas perkuliahan baru |
| **18** | `10-crud-create/02-create-success.png` | Operasi CREATE | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Notifikasi sukses simpan & data baru muncul di tabel |
| **19** | `11-crud-read/01-read-table.png` | Operasi READ | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Pembacaan tabel data tugas akademik dengan metrik dinamis |
| **20** | `12-crud-update/01-edit-form.png` | Operasi UPDATE | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Form pengubahan data tugas terisi data sebelumnya |
| **21** | `12-crud-update/02-edit-success.png` | Operasi UPDATE | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Perubahan data berhasil tersimpan & badge status terupdate |
| **22** | `13-crud-delete/01-delete-modal.png` | Operasi DELETE | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Modal dialog konfirmasi hapus permanen berbasis POST |
| **23** | `13-crud-delete/02-delete-success.png` | Operasi DELETE | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Data tugas terhapus permanen dari basis data cloud |
| **24** | `14-database-persistence/01-persistence-proof.png` | Persistensi Data | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Data tugas bertahan setelah reload browser & sesi baru |
| **25** | `14-database-persistence/02-empty-state-no-reseed.png` | Persistensi Data | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Seluruh data dihapus hingga kosong; empty state tertampil |
| **26** | `14-database-persistence/03-empty-state-after-refresh.png` | Persistensi Data | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Pasca hard reload browser, data terhapus TIDAK muncul kembali |
| **27** | `15-cicd-verification/01-git-commit-push.png` | Verifikasi CI/CD | 26 Okt 2026 | **Belum Lengkap** | Catatan Audit: Restriksi GDI desktop Windows service isolation mencegah penangkapan langsung jendela terminal lokal. Diverifikasi secara autentik via log Git CLI (Commit `91b2c6d`, `a3c3d51`) dan webhook trigger GitHub Actions Run ID `40308185002`. |
| **28** | `15-cicd-verification/02-actions-workflow-run.png` | Verifikasi CI/CD | 26 Okt 2026 | **Screenshot Aktual** | Terverifikasi: Workflow run sukses pasca push otomatis |
| **29** | `15-cicd-verification/03-live-site-updated.png` | Verifikasi CI/CD | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Domain live Azure terupdate menampilkan versi Build v3.0.2-CI-CD |
| **30** | `16-final-verification/01-full-site-verification.png` | Verifikasi Akhir | 26 Okt 2026 | **Screenshot Hasil Pengujian** | Terverifikasi: Verifikasi akhir sistem lengkap dengan data akademik aktif |

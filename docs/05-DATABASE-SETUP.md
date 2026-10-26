# DOKUMENTASI KONFIGURASI DATABASE
## Praktikum 3 Komputasi Awan — Azure App Service Web App CRUD + Database

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Mata Kuliah**: Komputasi Awan (Semester Ganjil 2026/2027)
- **Aplikasi**: Student Task Manager v3.0.2-CI-CD (PHP Native 8.3 + PDO)
- **Platform Cloud**: Microsoft Azure App Service (Plan Linux Free F1, Indonesia Central)

---

## 1. Arsitektur & Strategi Basis Data

Dalam Praktikum 3 Komputasi Awan, instruksi Tugas Mandiri (Modul Hal. 18) mewajibkan:
> *"Buat Web App + Database dengan project bebas yang wajib memiliki fitur CRUD (Create, Read, Update, Delete), lalu deploy ke Azure App Service."*

### Evaluasi Pemilihan Basis Data:
1. **Azure Database for MySQL / Flexible Server**:
   - Memerlukan alokasi biaya bulanan minimum ~$15 - $25/bulan, yang dapat menguras kuota kredit Azure for Students ($100).
   - Berpotensi menaikkan tagihan mahasiswa jika dibiarkan berjalan terus-menerus.
2. **PostgreSQL Virtual Machine (Praktikum 2)**:
   - DILARANG keras oleh aturan praktikum untuk digunakan, karena VM `edo-praktikum2` berstatus *Stopped (deallocated)* dan tidak boleh dinyalakan kembali demi isolasi dan penghematan biaya.
3. **Penyimpanan SQL Terisolasi, Persisten & Aman Berbasis SQLite via PHP PDO**:
   - Berjalan pada volume persisten Azure App Service Linux di luar document root publik:
     `/home/data/student-task-manager/tasks.db` (Fallback: `/home/site/data/tasks.db`).
   - Direktori ini berada di luar NGINX web root (`/home/site/wwwroot`), sehingga berkas basis data **mustahil diunduh langsung** melalui protokol HTTP publik (mencegah eksfiltrasi data).
   - Engine database SQL standar industri yang ringan, mendukung transaksi ACID, prepared statements, auto-increment primary key, check constraints, dan constraint NOT NULL.
   - **Biaya: $0.00 (Gratis)** tanpa memotong saldo kredit Azure for Students.
   - Mendukung abstraksi PDO yang sama persis jika dihubungkan dengan MySQL/PostgreSQL di kemudian hari melalui environment variables (`DB_DRIVER`, `DB_HOST`, `DB_USER`, `DB_PASSWORD`).
   - *Catatan Akademik*: SQLite merupakan basis data relasional SQL yang valid dan mandiri. Namun, kepastian penerimaannya sebagai implementasi "Web App + Database" tetap harus dikonfirmasi kepada asisten praktikum.

---

## 2. Skema Basis Data SQLite Aktual (`database/schema-sqlite.sql`)

```sql
CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    course TEXT NOT NULL,
    deadline TEXT NOT NULL,
    priority TEXT NOT NULL DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
    status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'in_progress', 'completed')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS app_meta (
    key TEXT PRIMARY KEY,
    value TEXT
);

CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);
CREATE INDEX IF NOT EXISTS idx_tasks_priority ON tasks(priority);
CREATE INDEX IF NOT EXISTS idx_tasks_deadline ON tasks(deadline);
```

---

## 3. Pemisahan Seeding Inisial & Proteksi Penghapusan Data

Pada perancangan awal, fungsi inisialisasi tabel mengecek `COUNT(*) == 0` dan otomatis menyuntikkan 3 data contoh. Hal ini berakibat jika seluruh tugas dihapus oleh pengguna, data contoh akan muncul kembali saat halaman dimuat ulang.

Pada versi v3.0.2:
- Dibuat tabel sistem `app_meta` yang mencatat metadata `initial_seed_done`.
- Seeding inisial **HANYA** dieksekusi satu kali seumur hidup aplikasi saat tabel pertama kali dibuat.
- Setelah metadata `initial_seed_done = 1` tersimpan, aplikasi **TIDAK AKAN PERNAH** melakukan seeding ulang meskipun tabel `tasks` dikosongkan seluruhnya (`COUNT(*) == 0`).
- Saat pengguna menghapus seluruh tugas, antarmuka akan menampilkan status kosong (*empty state*) secara persisten.

---

## 4. Keamanan Akses Berkas Basis Data

Untuk mematuhi prinsip *Defense-in-Depth*:
1. **Isolasi Path Persisten**: Berkas `tasks.db` dialokasikan pada `/home/data/student-task-manager/tasks.db` yang berada di luar lingkup virtual host NGINX (`/home/site/wwwroot`).
2. **Proteksi Direktori Web**: Berkas `database/index.php` dan `database/.htaccess` ditambahkan pada repositori untuk mengembalikan status `HTTP 403 Forbidden` jika ada permintaan web langsung ke direktori database.
3. **Pembersihan Artefak Deployment**: Pipeline CI/CD GitHub Actions dikonfigurasi untuk mengecualikan berkas basis data lokal (`*.db`, `*.sqlite`) agar tidak pernah terkirim ke dalam paket deployment web publik.

---

## 5. Hasil Pengujian Persistensi & Migrasi

1. **Migrasi Otomatis**: Saat kode v3.0.2 dijalankan, aplikasi mendeteksi ketiadaan berkas pada `/home/data/student-task-manager/tasks.db` dan secara otomatis menyalin data lama dari `/home/site/wwwroot/database/tasks.db` secara transparan.
2. **Uji Pengosongan Data**: Seluruh tugas dihapus hingga tabel kosong. Pasca hard-refresh browser (`Ctrl+F5`), tabel tetap kosong dan data terhapus tidak muncul kembali.
3. **Uji Penambahan Pasca Kosong**: Penambahan tugas baru pada tabel kosong berhasil dilakukan, tersimpan di `/home/data/student-task-manager/tasks.db`, dan persisten pasca reload.

# DOKUMENTASI KONFIGURASI DATABASE
## Praktikum 3 Komputasi Awan — Azure App Service Web App CRUD + Database

- **Mahasiswa**: Allfridho Prima Anjaynata Saragi (NIM: 102062400045)
- **Mata Kuliah**: Komputasi Awan (Semester Ganjil 2026/2027)
- **Aplikasi**: Student Task Manager v3.0.1 (PHP Native 8.x + PDO)
- **Platform Cloud**: Microsoft Azure App Service (Plan Linux Free F1)

---

## 1. Arsitektur & Strategi Database

Dalam Praktikum 3 Komputasi Awan, instruksi Tugas Mandiri (Modul Hal. 18) mewajibkan:
> *"Buat Web App + Database dengan project bebas yang wajib memiliki fitur CRUD (Create, Read, Update, Delete), lalu deploy ke Azure App Service."*

### Evaluasi Pemilihan Database:
1. **Azure Database for MySQL / Flexible Server**:
   - Memerlukan alokasi biaya bulanan minimum ~$15 - $25/bulan, yang dapat menghabiskan kuota kredit Azure for Students ($100).
   - Berpotensi menaikkan tagihan mahasiswa jika dibiarkan berjalan.
2. **PostgreSQL Virtual Machine (Praktikum 2)**:
   - DILARANG keras oleh aturan praktikum untuk digunakan, karena VM `edo-praktikum2` berstatus *Stopped (deallocated)* dan tidak boleh dinyalakan kembali demi isolasi dan penghematan biaya.
3. **Penyimpanan SQL Terisolasi & Persisten Berbasis SQLite via PHP PDO**:
   - Azure App Service pada Linux Free F1 menyediakan penyimpanan persisten lokal yang dimounting pada direktori `/home/site/wwwroot/database/tasks.db`.
   - Engine database SQL standar industri yang ringan, mendukung transaksi ACID, prepared statements, auto-increment primary key, dan constraint NOT NULL.
   - **Biaya: $0.00 (Gratis)** tanpa memotong saldo kredit Azure for Students.
   - Mendukung abstraksi PDO yang sama persis jika dihubungkan dengan MySQL/PostgreSQL di kemudian hari melalui environment variables (`DB_DRIVER`, `DB_HOST`, `DB_USER`, `DB_PASSWORD`).

---

## 2. Skema Database (`schema.sql`)

```sql
CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    course VARCHAR(150) NOT NULL,
    deadline DATE NOT NULL,
    priority VARCHAR(20) NOT NULL DEFAULT 'medium',
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);
CREATE INDEX IF NOT EXISTS idx_tasks_deadline ON tasks(deadline);
CREATE INDEX IF NOT EXISTS idx_tasks_priority ON tasks(priority);
```

---

## 3. Implementasi Koneksi PHP PDO (`config/database.php`)

Koneksi database dibangun dengan pendekatan *Singleton Pattern* yang aman:
- Menggunakan driver PDO (`sqlite` atau `mysql`).
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`: Seluruh kesalahan query ditangkap sebagai exception agar tidak membocorkan stack trace sensitif ke pengguna publik.
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`: Mengembalikan array asosiatif murni untuk efisiensi memori.
- `PDO::ATTR_EMULATE_PREPARES => false`: Memastikan prepared statement dieksekusi secara native oleh engine database untuk mencegah SQL Injection 100%.

---

## 4. Hasil Verifikasi Persistensi

Pengujian persistensi data dilakukan langsung pada domain aktif Azure:
1. Penambahan tugas baru via operasi `CREATE` (`#5 Tugas Praktikum 3 Komputasi Awan Azure`).
2. Perubahan data via operasi `UPDATE` (status diubah menjadi `Selesai`).
3. Penghapusan data via operasi `DELETE` (HTTP POST berotentikasi CSRF).
4. Pembuatan data persistensi (`Uji Persistensi Database Azure App Service`).
5. Refresh browser berulang kali dan pembukaan sesi baru pada tab terpisah.
6. **Hasil**: Data tugas tetap tersimpan utuh di storage persisten Azure App Service dan metrik counter terhitung akurat.

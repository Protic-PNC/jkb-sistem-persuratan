<div align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel" width="50" height="52">
  <h3>Sistem Persuratan Mahasiswa Jurusan Komputer dan Bisnis Politeknik Negeri Cilacap</h3>
  <p>Aplikasi web berbasis Laravel untuk mengelola surat pernyataan magang, pelanggaran akademik, dan pengunduran diri dengan alur persetujuan multi-level.</p>
  
  ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
  ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
  ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
</div>

---

## 🎯 Tentang Proyek

Sistem Persuratan Mahasiswa adalah aplikasi web yang dibangun dengan Laravel untuk mengelola berbagai jenis surat administratif mahasiswa. Sistem ini menyediakan alur persetujuan yang terstruktur dengan melibatkan berbagai peran pengguna dalam institusi pendidikan.

### ✨ Fitur Utama

-   📄 **Pernyataan Magang** - Pengajuan dan persetujuan surat pernyataan magang
-   ⚠️ **Pelanggaran Akademik** - Pencatatan dan pengelolaan pelanggaran dengan notifikasi email
-   🎓 **Pengunduran Diri** - Sistem pengajuan dengan alur persetujuan multi-departemen
-   👥 **Multi-Role System** - 6 peran pengguna dengan hak akses berbeda
-   📊 **Dashboard** - Monitoring dan pelaporan terintegrasi
-   🖨️ **Print System** - Cetak surat dalam format yang sesuai

---

## 🛠️ Persyaratan Sistem

| Komponen       | Versi Minimum                           |
| -------------- | --------------------------------------- |
| **PHP**        | 8.2+                                    |
| **Composer**   | Latest                                  |
| **Database**   | MySQL 8.0+ / PostgreSQL 13+ / SQLite 3+ |
| **Web Server** | Apache 2.4+ / Nginx 1.18+               |

---

## 🚀 Instalasi

### 1️⃣ Clone Repository

```bash
git clone https://github.com/username/persuratan-mahasiswa-evan.git
cd persuratan-mahasiswa-evan
```

### 2️⃣ Install Dependencies

```bash
composer install
```

### 3️⃣ Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Setup Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

### 📧 Konfigurasi Email (Opsional)

Untuk mengaktifkan fitur notifikasi email, tambahkan konfigurasi SMTP di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Politeknik Negeri Cilacap"
```

> **📝 Catatan untuk Gmail:**
>
> -   Gunakan **App Password** bukan password akun Gmail biasa
> -   Aktifkan 2-Factor Authentication di akun Gmail
> -   Generate App Password di: [Google Account Settings](https://myaccount.google.com/apppasswords)

### 5️⃣ Migrasi & Seeding

```bash
php artisan migrate --seed
```

#### 📥 Download Database (Opsional)

Jika Anda ingin menggunakan database yang sudah terisi dengan data contoh:

1. **Download database:** [📁 Download Database](https://drive.google.com/file/d/1WUF0vKfvCQY5sW8B4iOnEtDdtMsB9rpU/view?usp=sharing)
2. **Import ke database MySQL Anda**
3. **Skip langkah migrate --seed** jika menggunakan database download

### 6️⃣ Storage Link

```bash
php artisan storage:link
```

### 7️⃣ Jalankan Server

```bash
php artisan serve
```

🎉 **Aplikasi berhasil berjalan di:** `http://localhost:8000`

---

## 🔐 Akun Default

Setelah seeding berhasil, gunakan akun berikut untuk login:

```
Username: 123
Password: 123
```

---

## 📧 Konfigurasi Email Tambahan

### Gmail SMTP Setup

Sistem ini menggunakan Gmail SMTP untuk pengiriman notifikasi email. Berikut langkah-langkah konfigurasinya:

#### 1. Setup Gmail App Password

1. **Aktifkan 2-Factor Authentication** di akun Gmail Anda
2. Kunjungi [Google App Passwords](https://myaccount.google.com/apppasswords)
3. Pilih "Mail" dan generate password khusus aplikasi
4. Gunakan password yang dihasilkan sebagai `MAIL_PASSWORD`

#### 2. Konfigurasi Environment

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=generated-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Politeknik Negeri Cilacap"
```

#### 3. Test Email Configuration

Jalankan command berikut untuk memastikan email berfungsi dengan baik:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
Mail::raw('Test email dari Sistem Persuratan', function($message) {
    $message->to('test@example.com')
            ->subject('Test Email');
});
```

## 👥 Manajemen Peran & Hak Akses

<details>
<summary><b>🔧 Admin (Role ID: 1)</b></summary>

-   ✅ Mengelola semua pengguna (CRUD)
-   ✅ Mengelola kelas
-   ✅ Mengelola semua jenis surat
-   ✅ Approve/Reject semua surat
-   ✅ Cetak surat
-   ✅ Kirim reminder
-   ✅ Akses log sistem
</details>

<details>
<summary><b>🎓 Mahasiswa (Role ID: 2)</b></summary>

-   ✅ Ajukan surat pernyataan magang
-   ✅ Lihat pelanggaran akademik
-   ✅ Ajukan surat pengunduran diri
-   ✅ Upload dokumen pendukung
-   ✅ Cetak surat
-   ✅ Edit profil pribadi
</details>

<details>
<summary><b>👨‍💼 Ketua Jurusan (Role ID: 3)</b></summary>

-   ✅ Approve/Reject pelanggaran akademik
-   ✅ Approve/Reject pengunduran diri
-   ✅ Berikan alasan persetujuan/penolakan
-   ✅ Cetak surat
</details>

<details>
<summary><b>👨‍🏫 Dosen Wali (Role ID: 4)</b></summary>

-   ✅ Approve/Reject pelanggaran akademik
-   ✅ Approve/Reject pengunduran diri
-   ✅ Berikan alasan persetujuan/penolakan
-   ✅ Cetak surat
</details>

<details>
<summary><b>💰 Bagian Keuangan (Role ID: 5)</b></summary>

-   ✅ Approve/Reject pengunduran diri (berdasarkan status keuangan)
-   ✅ Cetak surat pengunduran diri
</details>

<details>
<summary><b>📚 Bagian Perpustakaan (Role ID: 6)</b></summary>

-   ✅ Approve/Reject pengunduran diri (berdasarkan status peminjaman)
-   ✅ Cetak surat pengunduran diri
</details>

---

## 📋 Alur Persetujuan

### 🔄 Pernyataan Magang

```
Mahasiswa → Admin → ✅ Approved
```

### ⚠️ Pelanggaran Akademik

```
Admin → Dosen Wali → Ketua Jurusan → ✅ Approved
```

### 🎓 Pengunduran Diri

```
Mahasiswa → Dosen Wali → Ketua Jurusan → Bagian Keuangan → Bagian Perpustakaan → ✅ Approved
```

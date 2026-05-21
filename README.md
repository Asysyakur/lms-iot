# LMS IoT Micro:bit

Sistem Learning Management System (LMS) berbasis Laravel + Vue.js untuk pembelajaran IoT Micro:bit di tingkat SMK.  
Aplikasi ini mendukung pembelajaran interaktif, monitoring siswa, quiz, evaluasi, LKPD, praktik, hingga monitoring progress dan laporan guru.

---

# ✨ Fitur Utama

## 👨‍🎓 Fitur Siswa

- Login siswa
- Pre-test & Post-test
- Materi pembelajaran PDF
- Video pembelajaran YouTube
- Progress membaca otomatis
- Pertanyaan pemantik
- Refleksi pembelajaran
- Quiz interaktif
- Upload tugas praktik
- Upload LKPD
- Evaluasi pembelajaran
- Tracking durasi akses materi
- Monitoring progress belajar

---

## 👨‍🏫 Fitur Guru

- Dashboard guru
- Manajemen akun siswa
- Kelola pertemuan
- Upload materi PDF
- Kelola quiz
- Monitoring aktivitas siswa
- Penilaian manual
- Export laporan Excel
- Monitoring pre-test & post-test
- Rekap seluruh aktivitas siswa

---

# 🛠️ Tech Stack

## Backend

- Laravel 13
- PHP 8.3
- MySQL

## Frontend

- Vue 3
- Inertia.js
- Tailwind CSS
- TypeScript

## Library Tambahan

- SweetAlert2
- Vue Sonner
- Axios
- PDF.js
- Laravel Excel (Maatwebsite)

---

# 📦 Persyaratan Sistem

Pastikan sudah menginstall:

| Software | Versi |
|---|---|
| PHP | >= 8.3 |
| Composer | Latest |
| Node.js | >= 20 |
| NPM | Latest |
| MySQL | Latest |
| Git | Latest |

---

# 🚀 Cara Menjalankan Project

# 1. Clone Repository

```bash
git clone https://github.com/Asysyakur/lms-iot.git
```

Masuk ke folder project:

```bash
cd lms-iot
```

---

# 2. Install Dependency Backend

```bash
composer install
```

---

# 3. Install Dependency Frontend

```bash
npm install
```

---

# 4. Copy File Environment

Linux / Git Bash:

```bash
cp .env.example .env
```

Windows CMD:

```cmd
copy .env.example .env
```

---

# 5. Generate APP_KEY

```bash
php artisan key:generate
```

---

# 6. Konfigurasi Database

Buka file:

```env
.env
```

Lalu ubah bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms-iot
DB_USERNAME=root
DB_PASSWORD=
```

---

# 7. Buat Database

Masuk ke phpMyAdmin / HeidiSQL / MySQL lalu jalankan:

```sql
CREATE DATABASE lms-iot;
```

---

# 8. Jalankan Migration

```bash
php artisan migrate
```

Jika ingin sekaligus seed:

```bash
php artisan migrate --seed
```

---

# 9. Storage Link

Penting untuk upload file agar bisa diakses browser.

```bash
php artisan storage:link
```

---

# 10. Jalankan Vite

```bash
npm run dev
```

---

# 11. Jalankan Laravel Server

Buka terminal baru lalu jalankan:

```bash
php artisan serve
```

---

# 12. Akses Project

```txt
http://127.0.0.1:8000
```

---

# 🔐 Login Default

## Guru

```txt
Username : guru
Password : password
```

---

## Siswa

Akun siswa dibuat melalui panel guru.

---

# 📁 Struktur Folder Penting

```txt
app/
├── Exports/
├── Http/
├── Models/

resources/
├── js/
│   ├── pages/
│   ├── layouts/
│   ├── components/

routes/
database/
storage/
public/
```

---

# 📊 Fitur Monitoring

Guru dapat melihat:

- Progress membaca siswa
- Durasi akses materi
- Jawaban pemantik
- Jawaban refleksi
- Nilai quiz
- Nilai praktik
- Hasil evaluasi
- Hasil LKPD
- Hasil pre-test
- Hasil post-test

---

# 📥 Export Excel

Project mendukung export:

- Pre-test
- Post-test
- Monitoring pertemuan
- Rekap seluruh aktivitas siswa

Menggunakan package:

```bash
maatwebsite/excel
```

Install package:

```bash
composer require maatwebsite/excel
```

---

# ⚠️ Jika Error ext-zip

Jika muncul error:

```txt
ext-zip missing
```

Aktifkan extension ZIP di PHP.

Buka file:

```txt
php.ini
```

Cari:

```ini
;extension=zip
```

Ubah menjadi:

```ini
extension=zip
```

Lalu restart Laragon / XAMPP.

---

# ⚡ Command Penting

## Clear Cache

```bash
php artisan optimize:clear
```

---

## Refresh Database

```bash
php artisan migrate:fresh --seed
```

---

## Build Production

```bash
npm run build
```

---

# 🧩 Jalankan Project Sekaligus

Gunakan 2 terminal:

## Terminal 1

```bash
npm run dev
```

## Terminal 2

```bash
php artisan serve
```

---

# 🖼️ Screenshot

Tambahkan screenshot project di sini.

---

# 👨‍💻 Developer

Developed by:

## Asysyakur

GitHub:

```txt
https://github.com/Asysyakur
```

Repository:

```txt
https://github.com/Asysyakur/lms-iot
```

---

# 📄 License

Project ini digunakan untuk kebutuhan pembelajaran dan penelitian.

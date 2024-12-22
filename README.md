<aside>

Wilson Hamonangan Ariyanto Hutapea
225150600111022
</aside>

Saya telah mendevelop sistem berbasis API yang aman menggunakan PHP untuk backend, plain HTML/CSS/JavaScript untuk frontend, dan MySQL sebagai database. Saya mengimplementasikan OAuth2 untuk autentikasi, memastikan enkripsi data, dan membuat sistem akun user sederhana.

### Gambaran Proyek

1. **Desain Arsitektur API**: Saya akan mendefinisikan endpoint API dan membuat diagram arsitektur sederhana.
2. **Implementasi Teknik Keamanan API**: Saya akan mengimplementasikan autentikasi OAuth2, enkripsi data, dan validasi input.
3. **Perlindungan Data Pribadi**: Saya akan memastikan data sensitif dienkripsi dan tidak disimpan dalam teks biasa.
4. **Pengujian Keamanan dan Dokumentasi**: Saya akan menguraikan metode pengujian dan mendokumentasikan implementasi.

### Langkah 1: Desain Arsitektur API

### Endpoint API

Berikut adalah endpoint API dasar yang akan saya implementasikan:

- **POST /api/register**: Mendaftarkan pengguna baru.
- **POST /api/login**: Mengautentikasi pengguna dan mengembalikan access token.
- **GET /api/user**: Mengambil informasi pengguna (memerlukan autentikasi).

### Diagram Arsitektur

```
+-------------------+          +-------------------+
|   Frontend (HTML) | <----->  |   PHP API Server  |
|                   |          |                   |
+-------------------+          +-------------------+
																		^
                                    |
                                    |
                                    v
                            +-------------------+
                            |     MySQL DB      |
                            +-------------------+

```

### Langkah 2: Implementasi Teknik Keamanan API

### 1. Menyiapkan Database

Siapkan database (`setup.sql`)

### 2. Implementasi PHP API

Membuat struktur untuk proyek ini:

**Koneksi Database (`config/database.php`)**

**Registrasi Pengguna (`api/register.php`)**

**Login Pengguna (`api/login.php`)**

**Dapatkan Info Pengguna (`api/user.php`)**

### 3. Implementasi Frontend

**Struktur HTML Dasar (`public/index.html`)**:

### Langkah 3: Menjalankan Proyek

1. **Siapkan Database**:
    - Buka phpmyadmin dan jalankan perintah dalam `setup.sql` untuk membuat database dan tabel pengguna.
2. **Jalankan PHP Server**:
    - Navigasi ke direktori proyek di terminal dan jalankan: `php -S localhost:8000 -t`
3. **Akses Aplikasi**:
    - Buka web browser dan kunjungi `http://localhost:8000`.

### Langkah 4: Pertimbangan Keamanan

- **Enkripsi Data**: Kata sandi di-hash menggunakan `password_hash()` untuk keamanan.
- **Validasi Input**: Pastikan input pengguna divalidasi sebelum diproses.
- **Manajemen Token**: Dalam lingkungan produksi, pertimbangkan menggunakan JWT untuk manajemen dan validasi token.

Link Github: [Secure-API GitHub](https://github.com/wilsonhutapea-ub/Secure-API)
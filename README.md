# Westland Coffee — Web App (Guest / Member / Admin)

Web app lengkap untuk UMKM **Westland Coffee** (Cut Nyak Dien, Pekanbaru) dengan:
- Landing page (guest) + halaman menu + halaman promo (tanpa login)
- Fitur member (register/login, profil, stamp/loyalty, riwayat transaksi, reward)
- Panel admin (dashboard, stok, penjualan, promosi, data member)

## Role & Akses
- **Guest**: `/`, `/menu`, `/promo`, `/loyalty`
- **Member** (login): `/member` (profil), `/member/rewards`, `/member/transactions`
- **Admin** (login): `/admin` (dashboard) + halaman manajemen

## Akun Admin (tanpa dummy)
Project ini **tidak** membuat akun dummy secara default.

Buat admin pertama kali dengan:
```bash
php artisan westland:make-admin
```

Opsional (kalau mau langsung ada menu/bahan/promo awal):
```bash
php artisan westland:seed-initial
```

## Tech Stack
- Laravel 12 (Blade)
- Tailwind CSS v4 (Vite)
## Database
### Opsi 1 — MySQL (disarankan kalau pakai Laragon/XAMPP)
1) Buat database (contoh): `westland_coffee`
2) Set `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=westland_coffee
DB_USERNAME=root
DB_PASSWORD=
```

### Opsi 2 — SQLite (paling cepat)
Pastikan ekstensi PHP aktif: **`pdo_sqlite`** dan **`sqlite3`**.

## Cara Menjalankan (Local)
### Prasyarat
- PHP 8.2+
- Composer
- Node.js 18+ / 20+
- Jika pakai SQLite: pastikan ekstensi PHP aktif: **`pdo_sqlite`** dan **`sqlite3`**

### Setup
1) Install dependencies
```bash
composer install
npm install
```

2) Env + key
```bash
copy .env.example .env
php artisan key:generate
```

3) Siapkan SQLite file (jika belum ada)
```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

4) Migrasi database
```bash
php artisan migrate:fresh
```

5) (Opsional) Storage link untuk upload gambar menu
```bash
php artisan storage:link
```

6) Jalankan aplikasi
Terminal A:
```bash
php artisan serve
```
Terminal B:
```bash
npm run dev
```

Buka: `http://127.0.0.1:8000`

7) Buat admin pertama kali
```bash
php artisan westland:make-admin
```

## Halaman yang Tersedia
### Halaman Umum (Guest)
- Landing page: `/`
- Menu: `/menu`
- Promo: `/promo`
- Program member: `/loyalty`
- Keranjang: `/cart`

### Member
- Register: `/member/register`
- Login: `/member/login`
- Profil: `/member`
- Reward: `/member/rewards`
- Riwayat transaksi: `/member/transactions`
- Checkout (simulasi bayar): `/member/checkout`

### Admin
- Login: `/admin/login`
- Dashboard: `/admin`
- Stok: `/admin/stocks` + log: `/admin/stocks/movements`
- Penjualan: `/admin/sales`
- Catat transaksi: `/admin/sales/create`
- Menu: `/admin/menu`
- Promosi: `/admin/promos`
- Member: `/admin/members`

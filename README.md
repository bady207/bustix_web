<h1 align="center">Selamat datang di Bustix! 🎫</h1>

## Apa itu Bustix?

**Bustix** adalah Website pemesanan tiket transportasi berbasis Laravel yang dikembangkan oleh [Bady Ubaidillah](https://github.com/bady207). Aplikasi ini dirancang untuk memudahkan pengguna dalam mencari rute, memilih kursi, dan memesan tiket transportasi secara online.

## Fitur yang Tersedia

- Autentikasi Multi-Role (Admin, Petugas, User)
- Manajemen User (CRUD)
- Manajemen Rute (CRUD)
- Manajemen Transportasi (CRUD)
- Kategori Transportasi (CRUD)
- Pemesanan Tiket
- Verifikasi Tiket oleh Petugas
- Riwayat Pembelian Tiket
- Ganti Username dan Password
- Dan fitur lainnya

## Release Date

**Release date: 05 Juni 2020**

---

## Default Account for Testing

**Admin Default Account**

- Username: `admin`
- Password: `admin123`

---

## Install

1. **Clone Repository**

bash
git clone https://github.com/adhiariyadi/Ticket-Laravel.git
cd Ticket-Laravel
composer install
cp .env.example .env


2. **Buka .env lalu ubah baris berikut sesuai dengan databasemu yang ingin dipakai**

bash
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=


3. **Instalasi website**

bash
php artisan key:generate
php artisan migrate --seed


4. **Jalankan website**

bash
php artisan serve

## Author

-   Facebook : <a href="https://www.facebook.com/profile.php?id=100093433156801&locale=id_ID"> Bady Ubaidillah</a>
-   LinkedIn : <a href="https://www.linkedin.com/in/bady-ubaidillah-207239339/"> Bady Ubaidillah</a>

## Contributing

Contributions, issues and feature requests di persilahkan.
Jangan ragu untuk memeriksa halaman masalah jika Anda ingin berkontribusi. **Berhubung Project ini saya sudah selesaikan sendiri, namun banyak fitur yang kalian dapat tambahkan silahkan berkontribusi yaa!**

## License

-   Copyright © 2020 Bady Ubaidillah.
-   **Ticket is open-sourced software licensed under the MIT license.**

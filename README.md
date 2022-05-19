# Tutorial Instalasi

1. Clone Repo Ini
2. Ganti nama file .env.example menjadi .env, sesuaikan data-data yang ada
3. Run command dibawah:
```
composer install
php artisan migrate
php artisan db:seed
```
4. Akses API login dengan username admin_arjasa dan password yang telah di set di .env
5. Simpan token login untuk mengakses API lainnya

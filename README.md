# Tutorial Instalasi

1. Clone Repo Ini
2. Ganti nama file .env.example menjadi .env, sesuaikan data-data yang ada
3. Run command dibawah:
```
composer install
php artisan migrate
php artisan db:seed
php -S localhost:8000 -t public
```
5. Akses API login dengan username admin_arjasa dan password yang telah di set di .env
6. Simpan token login untuk mengakses API lainnya

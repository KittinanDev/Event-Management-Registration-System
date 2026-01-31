# Quick Start (Laragon on Windows)

## Requirements
- Laragon with PHP 8.2+ (recommend 8.3)
- Git
- Composer
- Optional: Node.js LTS (for assets, hot reload)

## Clone into Laragon
```powershell
cd C:\laragon\www
git clone https://github.com/KittinanDev/Event-Management-Registration-System.git EM
cd EM
```

## Configure environment and install dependencies
```powershell
copy .env.example .env
composer install
php artisan key:generate
```

## Database
1. Open Laragon → MySQL → phpMyAdmin/HeidiSQL.
2. Create a database named `EM`.
3. Migrate tables:
```powershell
php artisan migrate
```

## Run the app
```powershell
php artisan serve
```
Then open http://127.0.0.1:8000

## One-click setup (optional)
Runs env copy, key generate, migrations, and builds assets:
```powershell
composer run setup
```

## Notes
- `.env.example` is tailored for Laragon: MySQL `root` (no password), `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` for smooth first run.
- For assets and hot reload, install Node.js and run:
```powershell
npm install
npm run dev
```

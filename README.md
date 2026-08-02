# pre.shop

`pre.shop` is a Laravel 13 + Livewire 4 community preorder marketplace demo. Makers publish campaigns, community members back them through a local simulated checkout, and admins review campaigns, orders, users, and wallets.

## Stack

- PHP 8.3+
- Laravel 13
- Livewire 4 with Blade
- Tailwind CSS 4 and Vite 8
- SQLite, MySQL, or another Laravel-supported database

## Run locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
npm run dev
```

The seeder is deterministic and is the canonical demo dataset. Re-run `php artisan migrate:fresh --seed` whenever you want a clean demo.

## Demo accounts

All demo accounts use the password `password`.

| Access | Email |
| --- | --- |
| Admin | `admin@preshop.test` |
| Community maker | `maya@preshop.test` |
| Community maker | `irfan@preshop.test` |
| Community member | `nadia@preshop.test` |
| Community member | `daniel@preshop.test` |

## Demo journeys

1. Open `/shop` and browse the active campaigns.
2. Open a campaign, choose an option and quantity, then complete the local checkout.
3. Sign in as Maya or Irfan to create and manage campaigns, view sales, and inspect wallet activity.
4. Sign in as the admin to review `/admin/overview`, users, campaigns, sales, and wallets.

Checkout is intentionally simulated. It creates a paid order and credits the campaign owner's wallet inside one database transaction; no external payment, OAuth, payout, or export service is required.

## Verification

```bash
php artisan test
npm run build
php artisan migrate:fresh --seed
```

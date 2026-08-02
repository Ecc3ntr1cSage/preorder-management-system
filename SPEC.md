# Preorder Management System — Specification Document

> **Project**: Preorder Management System ("Preshop")
> **Domain**: Multi-role SaaS for Malaysian preorder campaign management
> **Stack**: Laravel 10 + Jetstream (Livewire) + Tailwind CSS 3 + Vite
> **Payment**: Billplz (Malaysian FPX/online banking gateway)
> **Status**: Functional prototype — needs revival as demo project

---

## 1. Architecture Overview

### 1.1 Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | PHP / Laravel | ^8.1 / ^10.10 |
| Auth Scaffold | Laravel Jetstream + Fortify | ^4.1 |
| Frontend | Livewire + Alpine.js | ^3.0 |
| CSS | Tailwind CSS (Forms + Typography) | ^3.1 |
| Build | Vite + laravel-vite-plugin | ^4.0 / ^0.8 |
| Database | MySQL (default), SQLite viable for demo | — |
| Payments | Billplz API v3 (sandbox) | — |
| OAuth | Google Socialite (disabled/commented) | ^5.11 |
| Image Processing | Intervention Image | ^2.7 |
| Excel Export | Maatwebsite/Laravel-Excel | ^3.1 |
| Debugging | Laravel Telescope | ^4.17 |
| JS | Axios, FilePond | — |

### 1.2 Role-Based Access Control

| Role ID | Label | Routes Prefix | Description |
|---------|-------|---------------|-------------|
| 0 | Super Admin | `/admin/*` | Full system access |
| 1 | Admin | `/admin/*` | Manage campaigns, users, wallets |
| 2 | Business | `/dashboard/*` | Create/manage campaigns, view sales, withdraw |
| 3 | Customer | `/shop`, `/payment` | Browse, preorder, pay, view history |

Middleware: `RoleMiddleware` checks `$user->role_id` against allowed IDs. Gates defined in `AuthServiceProvider` (`admin-nav`, `business-nav`, `customer-nav`).

---

## 2. Data Model

### 2.1 Entity Relationship Diagram

```
User (role_id: 0|1|2|3)
 ├── hasMany Campaign              ──→ Campaign
 │    ├── hasMany Image             ──→ Image
 │    ├── hasMany Order             ──→ Order
 │    ├── hasMany Question          ──→ Question ── hasOne Reply
 │    ├── hasOne Coupon             ──→ Coupon
 │    └── hasMany Visitor           ──→ Visitor
 ├── hasOne Wallet                  ──→ Wallet
 │    └── hasMany Transaction       ──→ Transaction
 └── hasMany Order (as buyer)       ──→ Order
```

### 2.2 Table Definitions

#### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string UNIQUE | Username/business name |
| email | string UNIQUE | Login |
| email_verified_at | timestamp nullable | |
| phone | string nullable | |
| google_id | string nullable | For future OAuth |
| password | string nullable | Nullable for OAuth users |
| role_id | string | "0", "1", "2", "3" |
| links | json nullable | Social links: {facebook, instagram, tiktok} |
| profile_photo_path | string nullable | Jetstream profile photo |
| remember_token | string nullable | |

#### `campaigns`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | bigint FK→users | Owner (Business) |
| title | string UNIQUE | Campaign name |
| description | text | Short description |
| details | text | Full details (rich text) |
| currency | string | e.g. "RM" |
| price | int | In cents (sen), e.g. 10000 = RM100 |
| fee | int | 3% platform fee in cents |
| start_date | timestamp | Campaign start |
| end_date | timestamp | Campaign end |
| slug | string UNIQUE | URL slug |
| variations | json nullable | e.g. [{"name":"Size","values":"S,M,L"}] |
| shipping | json nullable | {west_malaysia, sarawak, sabah} in RM |
| status | int default 1 | 1=active, 2=ended |

#### `orders`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| billplz_id | string nullable | Billplz bill ID (set after payment) |
| collection_id | string | Billplz collection ID |
| campaign_id | bigint FK→campaigns | |
| user_id | bigint FK→users | Customer who ordered |
| email | string | Customer email |
| name | string | Customer name |
| phone | string | Customer phone |
| status | int | 0=pending, 1=paid, 2=shipped, 3=delivered |
| amount | int | Total in cents |
| discount | int | Coupon discount in cents |
| quantity | int | |
| fee | int | Platform fee in cents |
| shipping | int nullable | Shipping cost in cents |
| variations | string nullable | e.g. "Size: M, Color: Red" |
| paid | string | "true" / "false" |
| paid_at | string nullable | ISO datetime |
| address | string | |
| postcode | string | |
| state | string | Malaysian state |
| tracking_number | string nullable | |

#### `images`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| campaign_id | bigint FK→campaigns | |
| image | string | Filename (WebP format) |

#### `questions` / `replies`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| campaign_id / question_id | bigint FK | |
| question / reply | text | Content |

#### `coupons`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| campaign_id | bigint FK→campaigns | |
| code | string | Generated code |
| discount | int | Discount percentage (e.g. 10 = 10%) |
| limit | int | Max uses |
| usage | int default 0 | Current use count |
| expiry | timestamp | Coupon expiry |

#### `wallets`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | bigint FK→users | Business owner |
| bank_holder_name | string nullable | |
| bank_name | string nullable | |
| bank_account_number | string nullable | |
| earning | int | Lifetime earnings (cents) |
| balance | int | Available balance (cents) |
| status | int default 1 | 1=normal, 2=withdrawal pending |

#### `transactions`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| wallet_id | bigint FK→wallets | |
| current_balance | int | Pre-withdrawal balance |
| withdrawn_amount | int | |
| credited_amount | int | |
| final_balance | int | Post-transaction balance |
| status | int | 3=pending approval, 2=approved |

#### `visitors`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| campaign_id | bigint FK→campaigns | |
| user_id | bigint nullable FK→users | NULL for guests |
| session_id | string | Session tracking |

### 2.3 Monetary Handling

All monetary values stored as **integers in cents (sen)**. Division by 100 on display.
- Price entry: user enters `100.00` → stored as `10300` (price + 3% fee)
- Fee = `price * 1.03 - price`
- Shipping also stored in cents

---

## 3. Route Map

### 3.1 Public Routes

| Method | URI | Name | Purpose |
|--------|-----|------|---------|
| GET | `/` | `home` | Landing page |
| GET | `/{campaign}` | `customer.show` | Campaign detail (catch-all, no auth) |
| GET | `/billplz-callback` | `billplz-callback` | Billplz webhook callback |
| GET | `/billplz-redirect` | `billplz-redirect` | Billplz post-payment redirect |

### 3.2 Admin Routes (`role:0,1`)

| Method | URI | Name | Livewire Component |
|--------|-----|------|-------------------|
| GET | `/admin/overview` | `admin.overview` | AdminOverview |
| GET | `/admin/analytics` | `admin.analytic` | AdminAnalytics |
| GET | `/admin/campaigns` | `admin.campaign` | AdminCampaign |
| GET | `/admin/sales` | `admin.sale` | AdminSales |
| GET | `/admin/wallets` | `admin.wallet` | AdminWallet |
| GET | `/admin/storage` | `admin.storage` | AdminStorage (stub) |
| GET | `/admin/campaigns/{campaign}` | `admin.campaign.info` | AdminCampaignInfo |

### 3.3 Business Routes (`role:2`)

| Method | URI | Name | Livewire Component |
|--------|-----|------|-------------------|
| GET | `/dashboard/publish` | `business.publish` | Publish |
| GET | `/dashboard/campaigns` | `business.manage` | Manage |
| GET | `/dashboard/campaigns/{campaign}` | `business.info` | Info |
| GET | `/dashboard/my-sales` | `business.sales` | Sales |
| GET | `/dashboard/payouts` | `business.payout` | Payout |

### 3.4 Customer Routes (`role:3`)

| Method | URI | Name | Livewire Component |
|--------|-----|------|-------------------|
| GET | `/shop` | `customer.shop` | Shop |
| GET | `/payment` | `customer.payment` | Payment |
| GET | `/order/{order}` | `customer.invoice` | Invoice |
| GET | `/past-orders` | `customer.history` | History |

### 3.5 Auth Redirection

Login/Register responses redirect based on `role_id` (via `LoginResponse` / `RegisterResponse`):
- `role_id` 0 or 1 → `/admin/overview`
- `role_id` 2 → `/dashboard/publish`
- `role_id` 3 → `/shop`

---

## 4. Payment Flow

```
Customer selects variant + quantity
  → session('preorder') = {campaign_id, quantity, variations}
  → redirect to /payment
    → fill checkout form (name, email, phone, address, postcode, state)
    → postcode auto-detects state via config('countries.malaysia.postcodes')
    → shipping calculated based on state zone (west_malaysia / sarawak / sabah)
    → optional coupon code (validated: match, limit, expiry)
    → select FPX bank from config('banks')
  → POST /payment submits:
    1. Create Order in DB (status=0, billplz_id=null)
    2. POST to Billplz API v3 sandbox
    3. Redirect user to Billplz payment page
  → Billplz calls callback_url:
    - PaymentController::redirect()
    - If paid=true: update Order (paid=true, paid_at)
      - Increment coupon usage if discount applied
      - Credit campaign owner's wallet (earning + balance)
    - If paid=false: delete Order, redirect back to /payment
  → User redirected to /order/{billplz_id} (invoice)
```

---

## 5. Scheduled Tasks

### Daily at Asia/Singapore (Console\Kernel)
- Find campaigns where `end_date < now()` and `status = 1`
- Set campaign `status = 2` (ended)
- Set all related orders `status = 2` (shipped)

---

## 6. Key Business Logic

### 6.1 Campaign Creation (Business\Publish)
- Price entered as float (e.g. 100.00)
- Stored as `round(price * 100 * 1.03)` — 3% platform fee baked in
- Fee = stored price - (entered price * 100)
- Images: Upload → Intervention Image → encode WebP 90% → `storage/app/public/campaign/{id}-{random8}-{username}.webp`
- Variations stored as JSON array: `[{name: "Size", values: "S,M,L"}, ...]`
- Shipping stored as JSON: `{west_malaysia: "10", sarawak: "15", sabah: "20"}`

### 6.2 Coupon System (Business\Info)
- Generated with `Str::random(6)` (configurable)
- Discount is percentage off (integer)
- Validated on usage: `limit > usage`, `expiry > now()`
- When used: `coupon->increment('usage')`

### 6.3 Wallet & Withdrawal (Business\Payout → Admin\Wallet)
- Wallet created automatically per business user with 0 balance
- Earnings credited on each successful payment
- Business requests withdrawal → Transaction created (status=3), wallet status=2
- Admin approves → Transaction status=2, wallet status=1
- Minimum withdrawal: RM50

### 6.4 Visitor Tracking (Customer\Show)
- Tracks unique sessions per campaign
- Records `session_id`, `user_id` (nullable), `campaign_id`

### 6.5 Order Status System
| Status | Meaning | Set By |
|--------|---------|--------|
| 0 | Pending / unpaid | System on order creation |
| 1 | Paid | Billplz callback |
| 2 | Shipped | Scheduled task (auto) or manual |
| 3 | Delivered | Manual update |

---

## 7. Frontend Structure

### 7.1 Blade Layouts
- `layouts/guest.blade.php` — Used for landing, shop, campaign detail
- `layouts/app.blade.php` — Used for authenticated pages with navigation
- `components/` — 41 blade components including custom ones registered in `JetstreamServiceProvider`:
  - `dashboard-nav`, `dashboard-panel`, `admin-panel`, `admin-table`
  - `flash`, `filepond`, `glowing-card`, `gradient-card`
  - `textarea`, `loading-screen`, `button-custom`, `rich-text`

### 7.2 Styling
- Tailwind CSS with dark theme (dark backgrounds, gradients)
- Particle animation on landing page (`resources/js/particle-animation.js`)
- Custom gradients: `from-sky-400 via-indigo-500 to-purple-400`

### 7.3 Assets
- Images stored as WebP in `storage/app/public/campaign/`
- FPX bank logos in `public/asset/fpx-logo/`
- App logo: `public/asset/preorder.png`
- Checkout images: `public/asset/checkout.webp`, `checkout2.webp`

---

## 8. Configuration Reference

### `config/banks.php`
Maps FPX bank codes to display names and logos:
```
MB2U0227 → Maybank
BIMB0340 → Bank Islam
BCBB0235 → CIMB
RHB0218 → RHB
... (16 banks total)
```

### `config/countries.php`
Malaysia states (16) with postcode ranges for auto-detection:
- West Malaysia: Johor, Kedah, Kelantan, Melaka, N.Sembilan, Pahang, Penang, Perak, Perlis, Selangor, Terengganu, KL, Putrajaya
- East Malaysia: Sabah, Sarawak, Labuan

### `config/icons.php`
Embedded SVG icons for Facebook, Instagram, TikTok

### `config/size.php`
`['XS', 'S', 'M', 'L', 'XL', '2XL']` — (not actively used)

### Required `.env` Variables
| Variable | Purpose |
|----------|---------|
| `APP_KEY` | Laravel app key |
| `DB_*` | Database connection |
| `BILLPLZ_KEY` | Billplz API key |
| `BILLPLZ_SIGNATURE` | Billplz X-signature |
| `BILLPLZ_COLLECTION` | Billplz collection ID |
| `SESSION_DRIVER` | Set to `database` |
| `GOOGLE_*` | (Optional) Google OAuth credentials |

---

## 9. Revival Plan (Demo Readiness)

### Phase 1 — Environment & Dependencies
- Copy `.env.example` → `.env`, generate `APP_KEY`
- Configure DB (SQLite recommended for zero-config demo)
- Run `composer install`
- Run `npm install && npm run build`

### Phase 2 — Database Foundation
- Create SQLite database or MySQL database
- Run `php artisan migrate`
- Run `php artisan storage:link`

### Phase 3 — Demo Seeders (critical)
Create `Database\Seeders\DatabaseSeeder.php` with realistic data:

**Users (7 accounts):**
| Name | Email | Role | Purpose |
|------|-------|------|---------|
| Admin | admin@demo.com | 0 | Super admin |
| StylishThreads | business@demo.com | 2 | Fashion business |
| GadgetZone | gadget@demo.com | 2 | Electronics business |
| ArtisanCraft | craft@demo.com | 2 | Handicraft business |
| Ali | ali@demo.com | 3 | Customer |
| Sarah | sarah@demo.com | 3 | Customer |
| Muthu | muthu@demo.com | 3 | Customer |

**Campaigns (3-4):** Each with images, variations, shipping, coupons, visitor data

**Orders (5-6):** Mix of paid/pending, with proper amounts and shipping

**Wallets + Transactions:** For each business with realistic earnings

**Questions + Replies:** 2-3 Q&A threads on campaigns

### Phase 4 — Fix Incomplete Features
| Issue | Fix |
|-------|-----|
| `AdminStorage` stub | Either implement basic file listing or remove route |
| `user_data` table/model | Remove migration and model (unused placeholder) |
| Hardcoded values in `Payment.php` | Remove `$phone = '0183552589'` and `$address = 'Shah Alam'` |
| Placeholder text in `main.blade.php` | Replace "Export all sales data at the end of campaign" × 3 with unique feature descriptions |
| `GoogleController` | Either implement fully (uncomment routes, add to views) or remove entirely |
| Commented-out routes | Clean up commented route lines in `web.php` |

### Phase 5 — Documentation
- Update `README.md` with:
  - Project description and screenshots
  - Quick start guide (Prerequisites, install steps)
  - Demo credentials table
  - Billplz sandbox setup instructions
  - Architecture overview
  - Feature tour with screenshots of each panel
  - Screenshot generation script or manual steps

### Phase 6 — Verification
- `php artisan test` — all Jetstream tests pass
- `npm run build` — Vite builds without errors
- Manual smoke test:
  - Register as Business, create campaign, upload images
  - Register as Customer, browse shop, view campaign, ask question
  - Checkout flow (Billplz sandbox mode)
  - Admin login → view analytics, approve withdrawal
  - Scheduled task dry-run

---

## 10. Known Issues & Technical Debt

| Issue | Severity | Impact |
|-------|----------|--------|
| `Order.paid` column is string ("true"/"false") not boolean | Medium | Inconsistent with DB schema best practices |
| `amount` column name in `orders` conflicts with SQL reserved words | Low | Works but should be quoted |
| No model factories for most entities | High | Hard to write tests |
| No form request validation (inline in Livewire) | Medium | Mixed concerns |
| Google OAuth code present but disabled | Low | Dead code |
| No pagination on Shop page | Low | Performance issue with many campaigns |
| `UserData` model/table has no purpose | Low | Dead code |
| `Dashboard` Livewire component imported but commented out | Low | Dead code |
| Monetary calculations happen in Livewire components not services | Medium | Testing difficulty |
| Intervention Image `orientate()` may fail without EXIF data | Low | Silent failure |

---

## 11. File Map (Key Files)

```
app/
├── Actions/Fortify/        # Jetstream auth actions
├── Console/Kernel.php      # Scheduled campaign status update
├── Exceptions/Handler.php
├── Exports/ExportSales.php # Excel export
├── Http/
│   ├── Controllers/
│   │   ├── PaymentController.php  # Billplz callback handler
│   │   └── GoogleController.php   # OAuth (disabled)
│   ├── Middleware/
│   │   └── RoleMiddleware.php     # Role-based access
│   └── Responses/
│       ├── LoginResponse.php      # Role-based redirect
│       └── RegisterResponse.php
├── Livewire/
│   ├── Admin/ (7 components)
│   ├── Business/ (5 components)
│   ├── Customer/ (5 components)
│   └── Profile/ (1 component)
├── Models/ (10 models)
├── Providers/
│   ├── FortifyServiceProvider.php
│   ├── JetstreamServiceProvider.php
│   └── AuthServiceProvider.php    # Gates
└── View/Components/
    └── AppLayout.php / GuestLayout.php

config/
├── banks.php       # 16 FPX bank codes
├── countries.php   # Malaysia states + postcodes
├── icons.php       # Social media SVGs
├── size.php        # Size options
├── jetstream.php   # Features: accounts deletion only
└── fortify.php     # Features: reg, password reset, profile, password update

database/
├── migrations/ (16 files)
└── seeders/
    └── DatabaseSeeder.php  # (empty — needs implementation)

resources/
├── views/
│   ├── main.blade.php          # Landing page
│   ├── livewire/ (17 blade files)
│   ├── layouts/ (2 layouts)
│   └── components/ (41 components)
└── js/
    ├── app.js          # Particle animation + Alpine
    └── particle-animation.js

routes/
└── web.php             # All application routes
```

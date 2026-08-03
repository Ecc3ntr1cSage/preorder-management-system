# AGENTS.md

## Scope

These instructions apply to the entire repository. There are currently no narrower `AGENTS.md` files.

## Project intent

This repository is a self-contained preorder marketplace demo, not a production commerce platform. Preserve the ability to demonstrate the complete customer, maker and admin journeys without third-party credentials or services.

Current behaviour is authoritative. Do not restore removed Billplz, OAuth, role-ID, export or other legacy integrations unless the task explicitly requests them.

## Start here

Before changing code, inspect the full path involved:

1. Read `routes/web.php` and the relevant route middleware.
2. Read the routeable component in `app/Livewire` and its paired view in `resources/views/livewire`.
3. Read every model, relationship and migration touched by the flow.
4. Search all callers and tests before modifying shared behaviour.
5. Reuse existing components, model relationships, status constants and UI patterns.

Keep changes focused. This project intentionally uses direct Livewire-to-Eloquent flows; do not add repositories, services, DTOs, events, jobs or interfaces for a single use case.

## Repository facts

- PHP 8.3+, Laravel 13, Livewire 4 and Jetstream/Fortify/Sanctum
- Tailwind CSS 4 through Vite 8
- MySQL is the example environment default; tests use in-memory SQLite
- Application and scheduler timezone: `Asia/Kuala_Lumpur`
- All non-admin members can both publish campaigns and place orders
- Admin authorization is the `users.is_admin` boolean plus the `admin` middleware
- Registration is deliberately disabled
- Checkout and payouts are local simulations with no external requests
- Currency is Malaysian ringgit; persisted money is integer sen
- Demo data is deterministic and is part of the product experience

## Commands

Install and start:

```bash
composer install
npm ci
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
php artisan serve
npm run dev
```

Use `migrate:fresh` only against a disposable database: it drops every application table.

Verify:

```bash
php artisan test
npm run build
vendor/bin/pint --test
```

Useful focused checks:

```bash
php artisan test --filter=MarketplaceTest
php artisan test --filter=AdminOperationsTest
php artisan route:list --except-vendor
php artisan migrate:fresh --seed
php artisan optimize:clear
```

There is no configured JavaScript linter or unit-test runner. Do not invent commands that are absent from `package.json`.

## Architecture map

| Concern | Location |
| --- | --- |
| Web route map and access groups | `routes/web.php` |
| API route | `routes/api.php` |
| Routeable interactions | `app/Livewire/{Customer,Business,Admin,User}` |
| Paired UI | `resources/views/livewire` |
| Shared layouts and Blade primitives | `resources/views/layouts`, `resources/views/components` |
| Persistence and relationships | `app/Models` |
| Schema | `database/migrations` |
| Canonical demo records | `database/seeders/DatabaseSeeder.php` |
| Admin guard | `app/Http/Middleware/EnsureUserIsAdmin.php` |
| Login destination | `app/Http/Responses/LoginResponse.php` |
| Campaign expiry | `app/Console/Kernel.php` |
| Design tokens and shared CSS | `resources/css/app.css` |
| Progressive motion | `resources/js/*.js` |
| Feature coverage | `tests/Feature` |

Most pages are full-page Livewire components. Use Livewire public properties for form state, validation attributes or explicit `$this->validate(...)`, Eloquent relationships for persistence, and Blade for presentation. Add a new layer only when multiple real callers already need it.

## Domain invariants

### Access

- Public pages are `/`, `/shop` and `/{campaign}`.
- Protected member pages require both `auth` and `verified`.
- Admin pages additionally require `admin`; `EnsureUserIsAdmin` checks `is_admin`.
- Do not reintroduce `role_id` checks. The consolidation migration removes that column.
- Campaign management must remain owner-only. `Business\Info::mount()` currently enforces this.
- An invoice may be viewed only by its buyer, its campaign owner or an admin.
- Registration GET and POST remain disabled unless a task explicitly changes demo account policy.

The public `/{campaign}` route is a catch-all and must stay last. Put every new fixed top-level route above it or the slug binding will consume it.

### Money

- Store every monetary amount as integer sen. Convert to RM only at input/output boundaries.
- Campaign publishing stores the entered base price plus a 3% platform fee. `campaign.price` is the displayed unit price and `campaign.fee` is the unit platform fee.
- Checkout calculates `subtotal = campaign.price * quantity`.
- Shipping inputs are RM values in campaign JSON; checkout converts them to sen.
- Coupon discount is a percentage applied to the subtotal.
- The maker wallet credit is `order.amount - order.fee`.
- Never use formatted strings or binary floats as persisted monetary values.

Money and wallet changes require a regression test. Keep order creation, coupon use and wallet credit atomic. Keep admin withdrawal transitions locked, atomic and repeat-safe.

### Status values

Prefer the constants already defined on `Wallet` and `Transaction`. Campaign and order statuses are still integers shared with views and queries; change them only as a coordinated schema/model/UI/test update.

| Entity | Values |
| --- | --- |
| Campaign | `1` live, `2` ended |
| Order | `0` pending, `1` paid, `2` shipped, `3` delivered |
| Wallet | `1` active, `2` withdrawal pending |
| Transaction | `1` credited, `2` approved, `3` pending, `4` rejected |

The daily scheduler ends expired live campaigns and marks their orders shipped. Preserve the `Asia/Kuala_Lumpur` timezone and overlap protection.

### Checkout

- Campaign detail clears old preorder state, then writes `campaign_id`, quantity and selected variations to the session.
- `/payment` requires an authenticated, verified member and a valid preorder session.
- Checkout is intentionally simulated and creates an immediately paid order.
- Order creation, coupon usage and wallet credit happen in one database transaction.
- Successful checkout removes the preorder session to prevent duplicate submission.
- Do not add payment-provider configuration or network calls without an explicit requirement.

### Wallets and withdrawals

- Every seeded member owns one wallet; checkout also uses `firstOrCreate` defensively.
- A withdrawal requires complete bank details, at least RM50 and sufficient available balance.
- A pending withdrawal reserves funds by reducing the wallet balance and setting the wallet pending status.
- Admin approval finalizes the reservation. Rejection restores it.
- A repeated or stale admin decision must not move money twice.

### Campaigns and files

- Campaign route model binding uses `Campaign::getRouteKeyName()` and therefore resolves by slug.
- A new campaign title and slug must remain unique.
- Campaign variations and shipping are JSON-backed arrays.
- Publishing accepts at most five images, each at most 4 MB.
- Campaign uploads belong on the `public` disk under `campaign/`.
- Render them through `/storage/campaign/...`; local development needs `php artisan storage:link`.
- The seeder copies source images from `public/asset/product`. Do not remove those source files without changing the seeder and its tests.
- When deleting an uploaded image, delete both its storage object and database row. Be careful about the `public` disk path versus the default disk path.

### Demo data

`DatabaseSeeder` is the canonical demo fixture. It deliberately fixes Faker's seed and creates:

- 1 admin and 5 verified members, all with password `password`
- 8 campaigns: 3 live and 5 ended
- 14 paid, delivered orders and matching wallet credits
- 5 member wallets, campaign images, coupons, questions/replies and visitors

Keep the dataset repeatable and internally consistent. Schema or relationship changes must update the seeder and the seed assertions in `MarketplaceTest`. Never put production-like secrets or personal data into fixtures.

## Change guidance

### Livewire or UI change

- Change the component and its paired Blade view together when state and rendering both move.
- Follow nearby Livewire 4 patterns (`#[Rule]`, `#[Layout]`, locked state, named paginator pages).
- Reset pagination after changing search/filter state.
- Validate sort columns and directions before using them in queries.
- Keep queries scoped to the signed-in user or current campaign where appropriate.
- Reuse the design tokens and classes in `resources/css/app.css`; avoid a second design system.
- Preserve keyboard focus, labels, meaningful button types and reduced-motion behaviour.
- JavaScript motion must be progressive enhancement; core actions must remain Livewire/HTML driven.

### Route or authorization change

- Add fixed routes before the public campaign catch-all.
- Use route middleware for broad access and object-level checks for ownership.
- Add tests for unauthenticated, unauthorized and authorized cases.
- Do not rely on hiding a link as authorization.

### Database change

- Add a forward migration; do not rewrite historical migrations merely to alter an existing installed schema.
- Update fillable/casts/relationships, seeder records and affected tests in the same change.
- Verify against SQLite tests and the configured local database when using database-specific features.
- Use foreign keys or database constraints where they express a genuine invariant.
- Keep destructive data transforms explicit and reversible where practical.

### Checkout, coupon or wallet change

- Trace `Customer\Show` -> session -> `Customer\Payment` -> `Order`/`Wallet`/`Coupon` -> `Customer\Invoice`.
- Trace `Business\Payout` -> `Transaction`/`Wallet` -> `Admin\AdminWallet` for withdrawals.
- Use database transactions around multi-record money changes.
- Use row locks where concurrent admin or balance updates can race.
- Test successful behaviour plus duplicate/repeated execution.

### Authentication or account change

- Fortify and Jetstream provide most account routes and actions.
- Keep the custom modal login experience and `LoginResponse` destinations in mind.
- Admins go to `admin.overview`; members go to `dashboard`.
- Inspect the existing Jetstream feature tests before replacing framework behaviour.

## Code conventions

- Follow `.editorconfig`: UTF-8, LF, four spaces; Markdown may retain trailing spaces.
- Follow the existing PSR-12/Laravel style and let Pint settle PHP formatting.
- Prefer strict parameter and return types in new or touched code when Livewire supports them.
- Use model relationships instead of hand-written joins when the relationship already exists.
- Use existing status constants; do not scatter new magic numbers.
- Reuse native Laravel, Livewire, Blade and PHP features before adding dependencies.
- Avoid speculative abstractions, configuration switches and generic helpers.
- Comments should explain an invariant or surprising constraint, not narrate obvious code.
- Preserve unrelated working-tree changes. Never edit generated or installed dependency trees.

Do not edit or commit:

- `.env` or other credential-bearing environment files
- `vendor/`
- `node_modules/`
- `public/build/`
- `public/storage`
- caches under `bootstrap/cache`, `storage/framework` or PHPUnit result files

## Test expectations

Choose the smallest check that proves the change, then run the broader gates appropriate to its risk.

| Change | Minimum focused check | Handoff gate |
| --- | --- | --- |
| Documentation only | Inspect links/commands and `git diff --check` | No application test required |
| PHP formatting/refactor | Relevant PHPUnit test | `vendor/bin/pint --test` and `php artisan test` |
| Livewire interaction | Relevant feature class | `php artisan test` |
| Auth/access/ownership | Authorized and denied feature cases | `php artisan test` |
| Money/coupon/wallet | Transactional regression test including repeat case | `php artisan test` |
| Schema/seeder | Relevant feature test plus disposable migration reset | `php artisan test` and `php artisan migrate:fresh --seed` |
| Blade/CSS/JS | Relevant render test | `npm run build` and a browser smoke test when available |
| Route change | Target feature test and route inspection | `php artisan route:list --except-vendor` |

`phpunit.xml` already isolates tests with in-memory SQLite, array-backed cache/session, synchronous queues and an array mailer. Tests must not depend on the developer's `.env` database or external services.

## Security and data safety

- Treat all Livewire public properties, route parameters, uploads and API payloads as untrusted input.
- Validate at the action boundary, then authorize the specific record being changed.
- Never trust locked/client state as a substitute for reloading prices, ownership or balances from the database.
- Escape user content with normal Blade `{{ }}` output unless sanitized rich text is an explicit requirement.
- Do not expose bank account numbers, tokens, passwords or environment values in logs, fixtures or UI beyond the existing masked display.
- Preserve CSRF, signed URL, authentication, verification and Sanctum middleware.
- Do not weaken checks because this is a demo; demos are often deployed publicly.
- Ask before running destructive database commands if the target is not clearly disposable.

## Known sharp edges

- This is Laravel 13 running a retained classic application/kernel directory layout. Follow the repository's bootstrapping, not assumptions from a fresh Laravel 13 skeleton.
- The public campaign route catches any unmatched single segment.
- Some historical columns remain for compatibility (`billplz_id`, `collection_id`, `role_id` in an early migration); current flows do not use external Billplz or role IDs.
- JSON values may arrive as already-cast arrays through Eloquent. Check casts before adding manual `json_decode` calls.
- Money columns and some legacy timestamps are schema strings/integers with model casts layered on top. Preserve observable behaviour unless a task explicitly includes a migration.
- The local checkout credits the maker immediately and is not a real settlement model.

## Definition of done

A change is done when:

1. The requested behaviour is implemented at the shared root cause with the smallest coherent diff.
2. Access control, money, storage and demo-data invariants still hold.
3. A focused regression check exists for non-trivial logic.
4. Relevant tests/build/format checks pass, or the handoff clearly identifies what could not run and why.
5. Documentation is updated when commands, routes, credentials, architecture or demo behaviour changed.
6. Generated files, secrets and unrelated user changes are absent from the diff.

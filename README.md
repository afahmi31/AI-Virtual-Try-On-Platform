## What This System Does

- Seller logs in to a private dashboard.
- Seller manages products and product images.
- Seller configures FASHN AI credentials and model behavior from Settings.
- Customer opens seller public page, uploads model photo, and runs try-on.
- System creates try-on session, processes it via FASHN, and returns result.
- Public usage is protected by backend quota/rate-limit controls (IP + device + daily cap).

## AI Provider Used

Current provider integration:

- `FASHN AI` only (`App\Domain\AI\Providers\FashnProvider`)

Supported model profiles:

- `tryon-max`
- `tryon-v1.6`

Supported operating modes:

- Real provider mode (requires seller API key in Settings)
- Dummy mode per seller (for local/beta cost control)

## Main Features Available

### 1) Dashboard (Web)

- Route prefix: `/dashboard`
- Sections:
  - Dashboard metrics (products, recent try-on, FASHN credits)
  - Products management
  - Settings management
- Recent try-on table includes:
  - Request ID
  - Model
  - Created time
  - Status
  - Product name
  - IP (masked)
  - Preview
  - Details modal (input/output snapshot)

### 2) Product Management

- CRUD product for current seller only
- Product image source:
  - Uploaded file
  - External public URL
- AI metadata per product:
  - Prompt
  - Category (`auto`, `tops`, `bottoms`, `one-pieces`)
  - Garment photo type
  - Segmentation flag

### 3) Seller AI Settings

Configured from `/dashboard/settings`:

- FASHN API key (stored encrypted)
- Test API key against FASHN credits endpoint
- Model selection (`tryon-max` or `tryon-v1.6`)
- Model-specific generation config
- Dummy mode + dummy result URL + dummy model image URL
- Public generate limit per day
- Public limiter toggle:
  - Per IP
  - Per Device

Rule enforced:

- At least one limiter must stay enabled (IP or device).

### 4) Seller Store + Try-On

Public page route pattern:

- `/{seller_slug}/{product_ref?}`

`product_ref` supports:

- Product slug
- SKU (case-insensitive)

Try-on endpoints under:

- `/{seller_slug}/try-on/...`

Public flow:

- Customer selects product
- Uploads photo (or uses dummy model toggle if configured)
- Generates try-on
- Polls session status
- Sees result and recent history

### 5) Public Credit Protection

Backend protections implemented:

- Daily quota per seller public page
- Per IP limiter
- Per device limiter (`X-Tryon-Device-Id`)
- Polling throttle for status endpoint
- Generation is rejected at backend when limit is exhausted

Public quality mode behavior:

- Locked to cheapest mode on public flow (`standard` -> balanced/1k mapping).

### 6) Queue Processing

Try-on processing job:

- `App\Jobs\ProcessTryOnSessionJob`

Behavior:

- Creates provider job
- Polls status
- Updates session status
- Stores audit logs for provider request/response

Queue mode notes:

- Recommended: Redis queue worker in server/staging.
- Local fallback: if `QUEUE_CONNECTION=sync`, polling runs in-request (blocking behavior).

### 7) Media Retention

Current behavior:

- Each try-on session stores `expires_at` based on `TRYON_RETENTION_MINUTES`.
- Expiration timestamp is available for downstream cleanup policy (cron/job external).

Note:

- No built-in cleanup scheduler/job is registered in this repository.

## Active Route Summary

### Web Routes

- `GET /login`
- `POST /login`
- `POST /logout`
- `GET /dashboard`
- `GET /dashboard/products`
- `POST /dashboard/products`
- `PATCH /dashboard/products/{productId}`
- `DELETE /dashboard/products/{productId}`
- `POST /dashboard/products/{productId}/images`
- `GET /dashboard/settings`
- `POST /dashboard/settings`
- `POST /dashboard/settings/test-api-key`
- `POST /dashboard/model`
- `POST /{seller_slug}/try-on/sessions`
- `GET /{seller_slug}/try-on/quota`
- `GET /{seller_slug}/try-on/sessions/{sessionId}`
- `GET /{seller_slug}/try-on/sessions`
- `GET /{seller_slug}/{product_ref?}`

### API Routes (Sanctum)

- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`
- `GET /api/seller/me`
- `GET /api/seller/profile`
- `PATCH /api/seller/profile`
- `GET /api/seller/products`
- `POST /api/seller/products`
- `GET /api/seller/products/{id}`
- `PATCH /api/seller/products/{id}`
- `DELETE /api/seller/products/{id}`
- `POST /api/seller/products/{id}/images`
- `POST /api/tryon/sessions`
- `GET /api/tryon/sessions/{id}`

## Roles and Access

- Active dashboard role: `seller`
- Role middleware: `role:seller`
- Legacy multi-admin SaaS module is not part of active `core-app` flow.

## Tech Stack

- PHP `^8.2`
- Laravel `^12`
- PostgreSQL
- Redis (recommended for queue/cache)
- Laravel Sanctum
- Blade views + vanilla JS
- Vite build pipeline

## Local Setup

From `project` directory:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
```

Run app:

```bash
php artisan serve
npm run dev
```

Run queue worker (recommended):

```bash
php artisan queue:work
```

Or use combined dev script:

```bash
composer dev
```

## Seeded Demo Account

Created by `DatabaseSeeder`:

- Email: `seller@tryon.test`
- Password: `password`
- Seller slug: `ceriakid`

## Configuration Model (Important)

### A) Server-Level (`.env`)

Still controlled from env:

- Provider transport URL and status template
- Timeout and retry policy
- Queue/cache/db runtime behavior
- Retention and polling config

See `.env.example` for active keys.

### B) Seller-Level (Database via Settings UI)

Controlled from dashboard settings:

- API key
- Model selection and model config
- Dummy mode and dummy URLs
- Public generate limit and limiter toggles

## Testing

Run tests:

```bash
php artisan test
```

Current tests in repository focus on:

- Basic application response
- Seller API profile/product/image ownership flow
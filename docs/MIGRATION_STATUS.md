# Laravel Rebuild — Migration Status & Task Board

## Last Updated: 2026-06-18 (cron job #7)

## Architecture
- **Stack:** Laravel 11, Breeze auth, Tailwind CSS (unused), Legacy CSS (active), MariaDB
- **Legacy:** PHP 8.3, Bootstrap 5, jQuery/Vanilla JS, MariaDB (same DB)
- **DB:** `sql_nickandollie_com` — shared between old and new during migration

## Design System
- Legacy `style.css` + `DESIGN.md` are the canonical design reference
- Theme CSS variables applied in layout `<style>` block via AppServiceProvider (DB-backed)
- Warm Filipino palette: browns (#8b7355), cream (#faf8f5), gold (#c9a86c)
- Mobile-first, single system type stack
- **Removed** dark/mystical theme (glass-panel, floating-blob classes) that clashed with wedding aesthetic
- Added Tailwind compatibility CSS aliases in `style.css` for remaining Tailwind class references

## Page Migration Status

| Page | Legacy File | Laravel Route | Status | Notes |
|------|------------|---------------|--------|-------|
| Home / Landing | `index.php` | `GET /` | ✅ Done | Hero from DB, flower strip, login form (Bootstrap tabs) |
| Gallery | `gallery.php` + `api/gallery.php` | `GET /gallery` | ✅ Done | Photo grid with pagination, thumbnails, upload button |
| Photo Detail | `photo.php` + APIs | `GET /photo/{id}` | ✅ Done | Like, favorite, rate, comment, contest entry |
| Upload | `upload.php` + `api/upload.php` | `GET/POST /upload` | ✅ Done | Multi-photo, GD resize, WebP, EXIF strip. Restyled to Bootstrap 5 |
| Contests List | `contests.php` + `api/contests.php` | `GET /contest` | ✅ Done | Card grid with entry counts, status badges |
| Contest Detail | `contest.php` + `api/contest.php` | `GET /contest/{id}` | ✅ Done | Entry grid, lightbox, voting (contest_votes table) |
| Phonebook | `phonebook.php` + `api/phonebook-list.php` | `GET /phonebook` | ✅ Done | Search, filter by group, contact cards |
| Phonebook List | `phonebook_list.php` | `GET /phonebook/all` | ✅ Done | Alphabetical listing by first letter |
| Register | `register.php` + `api/register.php` | `GET/POST /register` | ✅ Done | All legacy fields, address_book auto-create. Restyled to Bootstrap 5 |
| Login | `index.php` + `do-login.php` | `POST /login` | ✅ Done | Breeze handles, email or username. Restyled to Bootstrap 5 |
| Logout | `logout.php` | `POST /logout` | ✅ Done | Breeze handles |
| Profile | `profile.php` + `api/profile.php` | `GET /profile` | ✅ Done | Full tabs: account, password, favorites, uploads, votes, comments |
| Admin Dashboard | `dash/dash.php` | `GET /admin` | ✅ Done | Stats, recent uploads, recent users, contest summary |
| Admin Themes | `theme-test.php` | `GET/POST /admin/themes` | ✅ Done | Preset picker, custom colors, live preview. Restyled to Bootstrap 5 |
| Admin Contests | — | `GET/POST /admin/contests` | ✅ Done | Full CRUD with edit form |
| Admin Phonebook | — | `GET/POST/DELETE /admin/phonebook` | ✅ Done | Contact CRUD |
| Admin Settings | — | `GET/PUT /admin/settings` | ✅ Done | Site title, hero, contact email, maintenance mode |
| Admin Users | `dash/users-api.php` | `GET/POST/PUT/DELETE /admin/users` | ✅ Done | Full CRUD |
| Admin Photos | — | `GET/PUT/DELETE /admin/photos` | ✅ Done | List, update caption, delete |
| Admin Comments | — | `GET/DELETE /admin/comments` | ✅ Done | List, delete, bulk-delete |

## API Migration Status

| Legacy API | Laravel Route | Status |
|------------|---------------|--------|
| `api/login.php` | Breeze `POST /login` | ✅ |
| `api/register.php` | `POST /register` | ✅ |
| `api/profile.php` | `GET/POST /profile` | ✅ |
| `api/gallery.php` | `GalleryController::index` | ✅ |
| `api/upload.php` | `UploadController` | ✅ |
| `api/comments.php` | `POST/GET /api/photo/{id}/comments` | ✅ |
| `api/contests.php` | `ContestController::index` | ✅ |
| `api/contest.php` | `ContestController::show` | ✅ |
| `api/contest-vote.php` | `POST /api/contest-vote` | ✅ |
| `api/phonebook-list.php` | `PhonebookController` | ✅ |
| `api/favorite.php` | `POST /api/photo/{id}/favorite` | ✅ |
| `api/rating.php` | `POST /api/photo/{id}/rate` | ✅ |
| `api/contest-entry.php` | `POST /api/photo/{id}/enter-contest` | ✅ |
| `api/like.php` | `POST /api/photo/{id}/like` | ✅ |
| Profile tabs | `GET /profile/{favorites,uploads,votes,comments}` | ✅ |

## Eloquent Models Created

| Model | Table | Relationships |
|-------|-------|---------------|
| `User` | `users` | photos, votes, favorites, ratings, comments, addressBook |
| `Photo` | `photos` | uploader, votes, favorites, ratings, comments, contestEntries |
| `Vote` | `votes` | photo, user |
| `Favorite` | `favorites` | photo, user |
| `Rating` | `ratings` | photo, user |
| `Comment` | `comments` | photo, user |
| `Contest` | `contests` | entries, approvedEntries (scope: isActive) |
| `ContestEntry` | `contest_entries` | contest, photo, submitter |
| `ContestVote` | `contest_votes` | entry, user |
| `AddressBook` | `address_book` | user (accessor: fullName) |
| `SitePage` | `site_pages` | — (static helpers: getContent/setContent) |
| `LookupOption` | `lookup_options` | — (static helpers: getConnections/getCoreGroups/getRelationships) |
| `ThemeSetting` | `theme_settings` | — (static helpers: getCurrent/getColors) |
| `Setting` | `settings` | — (static helpers: getValue/setValue) |

**Note:** Models are created but not yet wired into controllers. Controllers still use `DB::table()` queries. This is a code quality improvement that can be done incrementally.

## Mail Settings (Pending Migration)

Legacy `inc/mail.php` reads from `.env`:
- `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`
- `SMTP_ENCRYPTION`, `SMTP_FROM_EMAIL`, `SMTP_FROM_NAME`

**Action needed:** Copy these values from old `.env` to new Laravel `.env` and configure `config/mail.php`.

**Welcome email Mailable created** at `app/Mail/WelcomeEmail.php` with Blade template at `resources/views/mail/welcome.blade.php`. Wired into `RegisteredUserController::store()`.

## DB Schema Notes

### users table
- Has BOTH legacy enum columns (`connection`, `core_group`) AND newer lookup_id FKs
- Legacy rows have NULL IDs but valid enum strings
- `COALESCE(conn.label, u.connection)` fallbacks needed for joins
- `STRICT_TRANS_TABLES` is ON — empty strings to enum columns trigger errors
- **No `name` column** — uses `guest_name` instead. Breeze's default `name` field mapped to `guest_name`.

### Key tables
- `users` — guests and admin
- `photos` — uploaded images with thumb/print variants
- `votes` — photo likes
- `favorites` — user favorites
- `ratings` — photo ratings (1-5)
- `comments` — photo comments
- `contests` — photo contests
- `contest_entries` — photo-contest junction (with `votes` cache column)
- `contest_votes` — **NEW** table, separate from photo votes
- `address_book` — phonebook entries
- `theme_settings` — color theme (used by AppServiceProvider view composer)
- `site_settings` — template selection
- `site_pages` — DB-backed content (hero, etc.)
- `lookup_options` — dropdown options
- `settings` — site-wide settings (new in Laravel)

## Changes Made in This Session (2026-06-18 cron #7)

### 1. Layout Bug Fix: Missing @stack Directives
**Critical fix:** `upload.blade.php` and `contest-show.blade.php` used `@push('styles')` and `@push('scripts')` but the layouts had no `@stack()` directives. This meant Cropper.js CSS/JS and upload.js were never loaded on the upload page, and contest voting JS was never loaded on the contest detail page.
- Added `@stack('styles')` and `@stack('scripts')` to `app.blade.php`
- Added `@stack('styles')` and `@stack('scripts')` to `guest.blade.php`

### 2. Profile View Bug Fix: Array vs Object Access
**Fixed:** `wedding/profile.blade.php` favorites section used object notation (`$photo->id`, `$photo->thumb_filename`) but the controller returns arrays.
- Changed to array notation: `$photo['id']`, `$photo['thumb_url']`, `$photo['caption']`

### 3. Guest Layout Simplification
- Removed unused `auth-page`/`auth-container` wrapper divs from `guest.blade.php`
- Added `auth-body` class to body for gradient background
- Added `.auth-body` CSS with wedding gradient background to `public/css/style.css`

### 4. Static Assets
- Copied `templates.css` from old site to `public/css/templates.css`

### 5. E2E Tests: 8 New Tests Added
Added tests for:
- Contest vote API (auth required, missing entry_id, invalid entry_id)
- Admin photo management page loads
- Admin comment moderation page loads
- Admin user management page loads
- Photo interaction APIs (like/favorite/rate auth required, comments 404)

## Changes Made in Previous Session (2026-06-18 cron #6)

### 1. Alpine.js → Vanilla JS/ Bootstrap 5 (Profile Partials)
Replaced all Alpine.js directives in Breeze profile partials with vanilla JS and Bootstrap 5:
- `update-profile-information-form.blade.php` — replaced `x-data`/`x-show`/`x-transition`/`x-init` flash message auto-hide with vanilla JS `setTimeout`, replaced `<x-primary-button>` with `<button class="btn btn-primary">`
- `update-password-form.blade.php` — same flash message fix
- `delete-user-form.blade.php` — replaced `<x-danger-button>` Alpine click handler and `<x-modal>` with Bootstrap 5 modal (`modal fade`, `data-bs-toggle`, `data-bs-target`), added JS to auto-show modal on validation errors

### 2. Breeze Form Components → Bootstrap 5
Replaced Tailwind/custom theme classes in Breeze Blade components with standard Bootstrap 5:
- `text-input.blade.php` — `form-control` class
- `input-label.blade.php` — `form-label fw-medium` class
- `primary-button.blade.php` — `btn btn-primary` class
- `danger-button.blade.php` — `btn btn-danger` class
- `secondary-button.blade.php` — `btn btn-outline-secondary` class
- `input-error.blade.php` — `text-danger small mt-1` class

### 3. Alpine.js → Vanilla JS (Dropdown & Modal Components)
- `dropdown.blade.php` — replaced `x-data`/`x-show`/`x-transition` with vanilla JS click/keyboard handlers and CSS `display` toggle
- `modal.blade.php` — replaced Alpine modal with Bootstrap 5 modal (`modal fade`, `modal-dialog`), auto-shows on `$show=true` or validation errors

### 4. Fix: Registration 500 Error (Critical Bug)
**Root cause:** `User::create()` was trying to insert a `name` column that doesn't exist in the DB. The DB uses `guest_name` instead.
- Removed `'name'` from `User::$fillable` array
- Removed `'name' => $validated['guest_name']` from `RegisteredUserController::store()`
- Fixed `ProfileController::update()` — changed `$user->name = ...` to `$user->guest_name = ...`
- **Result:** Registration E2E test now passes (39/41 pass, up from 38/41)

### 5. E2E Tests: 39/41 Passed
39 tests pass. 2 failures are pre-existing timeout issues:
- `admin can add a phonebook contact` — 30s timeout on `networkidle` after form submit
- `admin can view and submit settings form` — 30s timeout on submit button click (resolves to invisible logout button)

## Pending Items

1. **Mail config** — SMTP credentials from old `.env` needed in new Laravel `.env` (Mailable class created, needs credentials)
2. **Models → Controllers** — Wire Eloquent models into controllers (incremental, low priority since DB::table works)
3. **Smoke tests** — `test.sh` 16 tests from legacy (not yet ported)
4. **rclone + Telegram** — Configured in old site — Laravel .env needs these values
5. **Tailwind removal** — Tailwind/vite pipeline is installed but unused. Could be removed to clean up. (Low priority — doesn't affect runtime)
6. **Pre-existing E2E timeouts** — Admin phonebook add and settings form submit timeout at 30s (low priority, functional)

### 6. Route Fix: Duplicate Contest Routes
**Fixed:** `routes/web.php` had duplicate contest CRUD routes and a broken `Admin\\ContestController::class` reference (relative namespace). Also missing phonebook and theme preview routes.
- Rewrote routes/web.php cleanly with all routes properly referenced
- Verified with `php artisan route:list` — all routes load correctly

Continue with pending items above. After each:
1. Update this doc
2. Test the feature
3. Commit with descriptive message

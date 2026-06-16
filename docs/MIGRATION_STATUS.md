# Laravel Rebuild — Migration Status & Task Board

## Last Updated: 2026-06-16 (cron job #8)

## Architecture
- **Stack:** Laravel 13, Breeze auth, Legacy CSS (active), MariaDB (shared with old site)
- **Legacy:** PHP 8.3, Bootstrap 5, jQuery/Vanilla JS, MariaDB
- **DB:** `sql_nickandollie_com` — shared between old and new during migration

## Design System
- Legacy `style.css` is the canonical design reference
- Theme CSS variables applied in layout `<style>` block via AppServiceProvider (DB-backed `ThemeService`)
- Warm Filipino palette: browns (#8b7355), cream (#faf8f5), gold (#c9a86c)
- Mobile-first, single system type stack
- **Removed** dark/mystical theme (glass-panel, floating-blob classes) — KEPT in CSS for reference but unused in views
- Tailwind installed but unused (low priority cleanup)

## Page Migration Status

| Page | Legacy File | Laravel Route | Status | Notes |
|------|------------|---------------|--------|-------|
| Home / Landing | `index.php` | `GET /` | ✅ Done | Hero from DB, flower strip, login form (Bootstrap tabs) |
| Gallery | `gallery.php` + `api/gallery.php` | `GET /gallery` | ✅ Done | Photo grid with pagination, thumbnails, upload button |
| Photo Detail | `photo.php` + APIs | `GET /photo/{id}` | ✅ Done | Like, favorite, rate, comment, contest entry |
| Upload | `upload.php` + `api/upload.php` | `GET/POST /upload` | ✅ Done | Multi-photo, GD resize, WebP, EXIF strip, cropper |
| Contests List | `contests.php` + `api/contests.php` | `GET /contest` | ✅ Done | Card grid with entry counts, status badges |
| Contest Detail | `contest.php` + `api/contest.php` | `GET /contest/{id}` | ✅ Done | Entry grid, lightbox, voting (contest_votes table) |
| Phonebook | `phonebook.php` + `api/phonebook-list.php` | `GET /phonebook` | ✅ Done | Search, filter by group, contact cards. API also live. |
| Phonebook List | `phonebook_list.php` | `GET /phonebook/all` | ✅ Done | Alphabetical listing by first letter |
| Register | `register.php` + `api/register.php` | `GET/POST /register` | ✅ Done | All legacy fields + address_book auto-create |
| Login | `index.php` + `do-login.php` | `POST /login` | ✅ Done | Breeze handles, email or username |
| Logout | `logout.php` | `POST /logout` | ✅ Done | Breeze handles |
| Profile | `profile.php` + `api/profile.php` | `GET /profile` | ✅ Done | Full tabs: account, password, favorites, uploads, votes, comments |
| Wedding Profile | `profile.php?id=X` | `GET /profile/{id?}` | ✅ Done | Public profile view with photos, favorites |
| Admin Dashboard | `dash/dash.php` | `GET /admin` | ✅ Done | Stats, recent uploads, recent users, contest summary |
| Admin Themes | `theme-test.php` | `GET/POST /admin/themes` | ✅ Done | Preset picker, custom colors, live preview |
| Admin Contests | `dash/contest.php` | `CRUD /admin/contests` | ✅ Done | Full CRUD with edit form |
| Admin Phonebook | `dash/address-book.php` | `CRUD /admin/phonebook` | ✅ Done | Contact CRUD |
| Admin Settings | — | `GET/PUT /admin/settings` | ✅ Done | Site title, hero, contact email, maintenance mode |
| Admin Users | `dash/users-api.php` | `CRUD /admin/users` | ✅ Done | Full CRUD |
| Admin Photos | `dash/admin-photos.php` | `GET/PUT/DELETE /admin/photos` | ✅ Done | List, update caption, delete |
| Admin Comments | `dash/comments.php` | `GET/DELETE /admin/comments` | ✅ Done | List, delete, bulk-delete |

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
| `api/phonebook-list.php` | `GET /api/phonebook-list` | ✅ Fixed in this session |
| `api/favorite.php` | `POST /api/photo/{id}/favorite` | ✅ |
| `api/rating.php` | `POST /api/photo/{id}/rate` | ✅ |
| `api/contest-entry.php` | `POST /api/photo/{id}/enter-contest` | ✅ |
| `api/like.php` | `POST /api/photo/{id}/like` | ✅ |
| Profile tabs | `GET /profile/{favorites,uploads,votes,comments}` | ✅ |

## Eloquent Models Created

| Model | Table | Status |
|-------|-------|--------|
| `User` | `users` | ✅ Fillable matches DB, accessor for `name` → `guest_name` |
| `Photo` | `photos` | ✅ Created |
| `Vote` | `votes` | ✅ Created |
| `Favorite` | `favorites` | ✅ Created |
| `Rating` | `ratings` | ✅ Created |
| `Comment` | `comments` | ✅ Created |
| `Contest` | `contests` | ✅ Created |
| `ContestEntry` | `contest_entries` | ✅ Created |
| `ContestVote` | `contest_votes` | ✅ Created |
| `AddressBook` | `address_book` | ✅ Created |
| `SitePage` | `site_pages` | ✅ Created |
| `LookupOption` | `lookup_options` | ✅ Created |
| `ThemeSetting` | `theme_settings` | ✅ Created |
| `Setting` | `settings` | ✅ Created |

**Note:** Models are created but not yet wired into controllers. Controllers still use `DB::table()` queries. This is a code quality improvement that can be done incrementally.

## Storage
- `storage/app/public/` has symlinks to old site's `originals/`, `thumbs/`, `print/` directories
- New uploads go to `storage/app/public/originals/` (Laravel default) → shared with old site

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

## Changes Made in This Session (2026-06-16 cron #8)

### 1. Fixed Api\PhonebookController (Bug Fix)
**Critical fix:** The controller at `Api\PhonebookController` was returning an empty entries array instead of actual phonebook data from the database.
- Replaced with full implementation matching legacy `api/phonebook-list.php` logic
- Returns all public phonebook entries with COALESCE for family_connection fallback
- Filters by user_type IN ('user', 'admin', 'partner') and show_in_phonebook = 1

## Pending Items

1. **Mail config** — SMTP credentials from old `.env` needed in new Laravel `.env` (Mailable class created, needs credentials)
2. **Models → Controllers** — Wire Eloquent models into controllers (incremental, low priority since DB::table works)
3. **Smoke tests** — `test.sh` 16 tests from legacy (not yet ported)
4. **rclone + Telegram** — Configured in old site — Laravel .env needs these values (informational, not breaking)
5. **Tailwind removal** — Tailwind/vite pipeline is installed but unused. Could be removed to clean up. (Low priority — doesn't affect runtime)
6. **Pre-existing E2E timeouts** — Admin phonebook add and settings form submit timeout at 30s (low priority, functional)
7. **Page Manager** — Admin settings has hero title/subtitle but the old `dash.php` had a full Page Manager tab for editing `site_pages` content. Could be added. (Low priority)

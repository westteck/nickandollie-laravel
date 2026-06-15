# Laravel Rebuild — Migration Status & Task Board

## Last Updated: 2026-06-15 (cron job #2)

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

## Changes Made in This Session (2026-06-15 cron #2)

### Auth pages restyled (login, register, reset-password)
Replaced Breeze/Tailwind component markup (`glass-panel`, `x-input-label`, `x-text-input`, `x-primary-button`) with Bootstrap 5 + legacy CSS classes (`form-control`, `form-select`, `btn btn-primary`, `auth-card`). Pages now match the wedding design system without Tailwind dependency.

### Route name fix (critical — caused 500 errors on all authenticated pages)
Routes inside the `name('admin.')->group()` had double-prefixed names (`admin.admin.users`, `admin.admin.photos`, `admin.admin.comments`). The navigation uses `route('admin.users')` which threw `RouteNotFoundException`. Fixed by removing the redundant `admin.` prefix from individual route names inside the group.

### Upload page restyled
Replaced Tailwind classes with Bootstrap 5 + legacy CSS. File input now uses `d-none` (Bootstrap) instead of `hidden` (Tailwind) so Playwright can find it. Dropzone uses standard CSS classes. Eliminated 500 error on upload page caused by the route name bug.

### Themes admin page restyled
Replaced Tailwind classes with Bootstrap 5 card grid. Preset names ("Fortune Gold", "Blush Romance") now render correctly without Tailwind CSS. Preview functionality updated to also set `--primary` etc. alongside `--color-*` variables.

### Navigation: public nav links for guests
Gallery, Contests, Phonebook links now shown to all visitors (not just authenticated users). Upload link still requires auth. Mobile nav also updated with Login/Create Account links for guests.

### Theme colors: DB-backed + `--color-*` aliases
- Added `AppServiceProvider` view composer that reads `theme_settings` table via `ThemeService::getCurrentColors()` and injects `$themeColors` into all views
- App layout now uses DB-driven colors for `--primary`, `--secondary`, etc.
- Added `--color-primary`, `--color-secondary`, `--color-accent`, `--color-background`, `--color-text` CSS variable aliases for theme system compatibility

### E2E test results: 40 passed, 1 failed (was 30 passed, 7 failed)
All previously failing tests now pass. Remaining failure: "photo detail page has comment form" — 30s timeout, likely a test timing issue (button text mismatch: test looks for `button[type="submit"]:has-text("Comment")` but button says "Post Comment").

## Pending Items

1. **Mail config** — SMTP credentials from old `.env` (Mailable class created, needs credentials)
2. **E2E: comment form test** — Fix button text or test selector (minor)
3. **Smoke tests** — `test.sh` 16 tests from legacy (not yet ported)
4. **rclone + Telegram** — Configured in old site — Laravel .env needs these values
5. **Tailwind removal** — Tailwind/vite pipeline is installed but unused. Could be removed to clean up.
6. **Wedding profile** — `WeddingProfileController` + `wedding/profile.blade.php` verified working (E2E test passes)

## Resumable Work

Continue with pending items above. After each:
1. Update this doc
2. Test the feature
3. Commit with descriptive message

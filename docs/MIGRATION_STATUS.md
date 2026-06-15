# Laravel Rebuild — Migration Status & Task Board

## Last Updated: 2026-06-15 (cron job #3)

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

## Eloquent Models Created (2026-06-15 cron #3)

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

## Changes Made in This Session (2026-06-15 cron #3)

### 1. Eloquent Models Created (14 models)
Created proper Eloquent models for all database tables: Photo, Vote, Favorite, Rating, Comment, Contest, ContestEntry, ContestVote, AddressBook, SitePage, LookupOption, ThemeSetting, Setting, plus updated User. Models include relationships, accessors, scopes, and helper methods. Controllers still use `DB::table()` — models available for incremental migration.

### 2. Admin Dashboard — Tailwind → Bootstrap 5
Rewrote `admin/dashboard.blade.php` from Tailwind classes to Bootstrap 5 + legacy CSS (card grid, stats row, table, quick links).

### 3. Admin Contests — Tailwind → Bootstrap 5
Rewrote `admin/contests.blade.php` from Tailwind to Bootstrap 5 (table, form, status badges, action buttons).

### 4. Admin Phonebook — Tailwind → Bootstrap 5
Rewrote `admin/phonebook.blade.php` from Tailwind to Bootstrap 5 (table, form, contact CRUD).

### 5. Upload JS — Tailwind → Bootstrap classes
Fixed `public/js/upload.js` preview grid to use Bootstrap classes + inline styles instead of Tailwind (`bg-slate-100`, `rounded-xl`, `aspect-square`, etc.).

### 6. CSS — Tailwind compatibility aliases
Added ~150 lines of Tailwind-to-legacy-CSS compatibility aliases in `style.css` (`glass-panel`, `bg-slate-*`, `rounded-xl`, `rounded-2xl`, `aspect-square`, `bg-green-100`, `text-green-700`, etc.). This ensures remaining partial Tailwind references in views render correctly.

### 7. E2E tests: 41/41 passed
All 41 tests pass after all template/JS/CSS changes.

## Pending Items

1. **Mail config** — SMTP credentials from old `.env` (Mailable class created, needs credentials)
2. **Models → Controllers** — Wire Eloquent models into controllers (incremental, low priority since DB::table works)
3. **Smoke tests** — `test.sh` 16 tests from legacy (not yet ported)
4. **rclone + Telegram** — Configured in old site — Laravel .env needs these values
5. **Tailwind removal** — Tailwind/vite pipeline is installed but unused. Could be removed to clean up.
6. **Contest vote API test** — Add E2E test for contest voting flow

## Resumable Work

Continue with pending items above. After each:
1. Update this doc
2. Test the feature
3. Commit with descriptive message

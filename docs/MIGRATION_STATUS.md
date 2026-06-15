# Laravel Rebuild — Migration Status & Task Board

## Last Updated: 2026-06-15 (cron job)

## Architecture
- **Stack:** Laravel 11, Breeze auth, Tailwind CSS (unused), Legacy CSS (active), MariaDB
- **Legacy:** PHP 8.3, Bootstrap 5, jQuery/Vanilla JS, MariaDB (same DB)
- **DB:** `sql_nickandollie_com` — shared between old and new during migration

## Design System
- Legacy `style.css` + `DESIGN.md` are the canonical design reference
- Theme CSS variables applied in layout `<style>` block
- Warm Filipino palette: browns (#8b7355), cream (#faf8f5), gold (#c9a86c)
- Mobile-first, single system type stack
- **Removed** dark/mystical theme (glass-panel, floating-blob classes) that clashed with wedding aesthetic

## Page Migration Status

| Page | Legacy File | Laravel Route | Status | Notes |
|------|------------|---------------|--------|-------|
| Home / Landing | `index.php` | `GET /` | ✅ Done | Hero from DB, flower strip, login form (Bootstrap tabs) |
| Gallery | `gallery.php` + `api/gallery.php` | `GET /gallery` | ✅ Done | Photo grid with pagination, thumbnails, upload button |
| Photo Detail | `photo.php` + APIs | `GET /photo/{id}` | ✅ Done | Like, favorite, rate, comment, contest entry |
| Upload | `upload.php` + `api/upload.php` | `GET/POST /upload` | ✅ Done | Multi-photo, GD resize, WebP, EXIF strip |
| Contests List | `contests.php` + `api/contests.php` | `GET /contest` | ✅ Done | Card grid with entry counts, status badges |
| Contest Detail | `contest.php` + `api/contest.php` | `GET /contest/{id}` | ✅ Done | Entry grid, lightbox, voting (contest_votes table) |
| Phonebook | `phonebook.php` + `api/phonebook-list.php` | `GET /phonebook` | ✅ Done | Search, filter by group, contact cards |
| Phonebook List | `phonebook_list.php` | `GET /phonebook/all` | ✅ Done | Alphabetical listing by first letter |
| Register | `register.php` + `api/register.php` | `GET/POST /register` | ✅ Done | All legacy fields, address_book auto-create |
| Login | `index.php` + `do-login.php` | `POST /login` | ✅ Done | Breeze handles, email or username |
| Logout | `logout.php` | `POST /logout` | ✅ Done | Breeze handles |
| Profile | `profile.php` + `api/profile.php` | `GET /profile` | ✅ Done | Full tabs: account, password, favorites, uploads, votes, comments |
| Admin Dashboard | `dash/dash.php` | `GET /admin` | ✅ Done | Stats, recent uploads, recent users, contest summary |
| Admin Themes | `theme-test.php` | `GET/POST /admin/themes` | ✅ Done | Preset picker, custom colors, live preview |
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
- `theme_settings` — color theme
- `site_settings` — template selection
- `site_pages` — DB-backed content (hero, etc.)
- `lookup_options` — dropdown options
- `settings` — site-wide settings (new in Laravel)

## Changes Made in This Session (2026-06-15 cron)

1. **Gallery view — unstubbed** | `resources/views/wedding/gallery.blade.php` — replaced placeholder cards with real photo grid, pagination, and upload button. GalleryController already fetched data; now the view renders it.

2. **Contest vote fix** | Created `contest_votes` migration (`database/migrations/2026_06_15_120000_create_contest_votes_table.php`) — new table with `(contest_entry_id, user_id)` unique constraint. Fixed `POST /api/contest-vote` to use `contest_votes` table instead of reusing `votes` (photo likes), preventing contest votes from being mixed with photo likes.

3. **Contest detail view fix** | `resources/views/contest-show.blade.php` — changed `data-id` (was `photo_id`) to separate `data-entry-id` (contest_entries.id) and `data-photo-id`. Fixed JS vote handlers to send `entry_id` correctly.

4. **App layout — theme alignment** | `resources/views/layouts/app.blade.php` — replaced dark/mystical theme (glass-panel, floating-blob, dark mode classes) with warm Filipino wedding theme. Now uses legacy `style.css`, Bootstrap 5, Font Awesome. CSS variables from DESIGN.md applied in `:root`.

5. **Navigation rebuilt** | `resources/views/layouts/navigation.blade.php` — replaced Tailwind-based nav with custom CSS nav that matches wedding aesthetic. Responsive, mobile-first, clean.

6. **Home page aligned** | `resources/views/home.blade.php` — replaced Tailwind classes with Bootstrap 5 + legacy CSS classes (hero, auth-card, form-control, btn-primary). Preserved flower strip, hero from DB, login form.

7. **Guest layout fixed** | `resources/views/layouts/guest.blade.php` — simplified to use legacy CSS, removed Vite/Tailwind dependencies. Auth pages (register, login, etc.) now style correctly.

## Pending Items

1. **Mail config** — SMTP credentials from old `.env` (Mailable class created, needs credentials)
2. **E2E tests** — Playwright tests at `e2e/site.spec.ts` — run and verify
3. **Smoke tests** — `test.sh` 16 tests from legacy (not yet ported)
4. **rclone + Telegram** — Configured in old site — Laravel .env needs these values
5. **Tailwind removal** — Tailwind/vite pipeline is installed but unused. Could be removed to clean up.
6. **Wedding profile** — `WeddingProfileController` exists but blade view at `wedding/profile.blade.php` needs verification

## Resumable Work

Continue with pending items above. After each:
1. Update this doc
2. Test the feature
3. Commit with descriptive message

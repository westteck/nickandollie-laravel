# Laravel Rebuild — Migration Status & Task Board

## Last Updated: 2026-06-15

## Architecture
- **Stack:** Laravel 11, Breeze auth, Tailwind CSS, Alpine.js, MariaDB
- **Legacy:** PHP 8.3, Bootstrap 5, jQuery/Vanilla JS, MariaDB (same DB)
- **DB:** `sql_nickandollie_com` — shared between old and new during migration

## Page Migration Status

| Page | Legacy File | Laravel Route | Status | Notes |
|------|------------|---------------|--------|-------|
| Home / Landing | `index.php` | `GET /` | ✅ Done | Hero from DB, flower strip, login/register tabs |
| Gallery | `gallery.php` + `api/gallery.php` | `GET /gallery` | ✅ Done | Photo grid with pagination, thumbnails |
| Photo Detail | `photo.php` + APIs | `GET /photo/{id}` | ✅ Done | Like, favorite, rate, comment, contest entry |
| Upload | `upload.php` + `api/upload.php` | `GET/POST /upload` | ✅ Done | Dropzone, cropper, progress bar, multi-file |
| Contests List | `contests.php` + `api/contests.php` | `GET /contest` | ✅ Done | Card grid with entry counts |
| Contest Detail | `contest.php` + `api/contest.php` | `GET /contest/{id}` | ✅ Done | Entry grid, lightbox, voting |
| Phonebook | `phonebook.php` + `api/phonebook-list.php` | `GET /phonebook` | ✅ Done | Search, filter by group, contact cards |
| Phonebook List | `phonebook_list.php` | `GET /phonebook/all` | ✅ Done | Alphabetical listing by first letter |
| Register | `register.php` + `api/register.php` | `GET/POST /register` | ✅ Done | All legacy fields, connection/group cascades, address book auto-create |
| Login | `index.php` + `do-login.php` | `POST /login` | ✅ Done | Breeze handles, email or username |
| Logout | `logout.php` | `POST /logout` | ✅ Done | Breeze handles |
| Profile | `profile.php` + `api/profile.php` | `GET /profile` | ✅ Done | Full tabs: account, password, favorites, uploads, votes, comments. Selfie camera modal wired. |
| Admin Dashboard | `dash/dash.php` | `GET /admin` | ✅ Done | Stats, recent uploads, recent users, contest summary |
| Admin Themes | `theme-test.php` | `GET/POST /admin/themes` | ✅ Done | Preset picker, custom colors, live preview |
| Admin Contests | — | `GET/POST /admin/contests` | ✅ Done | CRUD with edit form |
| Admin Phonebook | — | `GET/POST/DELETE /admin/phonebook` | ✅ Done | Contact CRUD |
| Admin Settings | — | `GET/PUT /admin/settings` | ✅ Done | Site title, hero, contact email, maintenance mode |

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
| `api/phonebook-list.php` | `PhonebookController` | ✅ |
| `api/favorite.php` | `POST /api/photo/{id}/favorite` | ✅ |
| `api/rating.php` | `POST /api/photo/{id}/rate` | ✅ |
| `api/contest-entry.php` | `POST /api/photo/{id}/enter-contest` | ✅ |
| `api/contest-vote.php` | `POST /api/contest-vote` | ✅ |
| Profile favorites | `GET /profile/favorites` | ✅ |
| Profile uploads | `GET /profile/uploads` | ✅ |
| Profile votes | `GET /profile/votes` | ✅ |
| Profile comments | `GET /profile/comments` | ✅ |

## Mail Settings (Pending Migration)

Legacy `inc/mail.php` reads from `.env`:
- `SMTP_HOST` — Gmail SMTP host
- `SMTP_PORT` — SMTP port
- `SMTP_USERNAME` — SMTP username
- `SMTP_PASSWORD` — SMTP password
- `SMTP_ENCRYPTION` — Encryption method
- `SMTP_FROM_EMAIL` — From email address
- `SMTP_FROM_NAME` — From name

**Action needed:** Copy these values from old `.env` to new Laravel `.env` and configure `config/mail.php`.

**Welcome email Mailable created** at `app/Mail/WelcomeEmail.php` with Blade template at `resources/views/mail/welcome.blade.php`. Wired into `RegisteredUserController::store()` — sends on registration (non-blocking, errors logged).

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
- `contest_entries` — photos entered in contests
- `address_book` — phonebook entries
- `theme_settings` — color theme
- `site_settings` — template selection
- `site_pages` — DB-backed content (hero, etc.)
- `lookup_options` — dropdown options
- `settings` — site-wide settings (new in Laravel)

## Pending Items

1. ~~Phonebook alphabetical listing page~~ ✅
2. ~~Contest vote API~~ ✅
3. **Mail config** — SMTP credentials from old `.env` (Mailable class created, needs credentials)
4. ~~Welcome email~~ ✅ Mailable created and wired into registration
5. ~~Selfie modal~~ ✅ Camera capture modal wired in profile page
6. ~~Theme CSS~~ ✅ Copied to `public/css/themes/`
7. ~~Flower SVGs~~ ✅ Copied to `public/images/flowers/`
8. ~~robots.txt~~ ✅ Updated for Laravel paths
9. ~~sitemap.xml~~ ✅ Updated for Laravel routes
10. **E2E tests** — Comprehensive Playwright tests exist at `e2e/site.spec.ts`
11. **Smoke tests** — `test.sh` 16 tests from legacy (not yet ported)
12. **rclone + Telegram** — Configured in old site — Laravel .env needs these values
13. **Admin user management** — `dash/users-api.php` not ported (create/edit/delete users)
14. **Admin comment moderation** — `admin/comments.php` not ported
15. **Admin photo management** — `admin/gallery.php` not ported

## Resumable Work

Continue with pending items above. After each:
1. Update this doc
2. Test the feature
3. Commit with descriptive message

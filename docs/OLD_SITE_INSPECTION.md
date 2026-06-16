# Old Site Inspection Report — Laravel Migration Status
## Source: /www/wwwroot/nickandollie.com/
## Last Updated: 2026-06-16

---

## Public Pages (root .php files)

| File | Features | Port Status |
|------|----------|-------------|
| `index.php` | Hero landing, theme switcher (?theme=), API routing, CSP headers | ✅ `home.blade.php` — hero from DB, flower strip, login/register tabs |
| `gallery.php` | Photo grid JS-driven, "Upload" button | ✅ `wedding.gallery.blade.php` + `GalleryController` |
| `photo.php` | Lightbox, favorite toggle, star rating, contest entry, comments | ✅ `wedding.photo.blade.php` + `PhotoController` |
| `contests.php` | Contest cards with top-3 thumbnails, status badges | ✅ `contest.blade.php` + `ContestController` |
| `contest.php` | Entry grid with lightbox, vote buttons, prev/next nav | ✅ `contest-show.blade.php` + `ContestController::show` |
| `enter-contest.php` | NOW REDIRECTS to gallery.php | N/A — contest entry from photo page only |
| `phonebook.php` | Grouped contact list, opt-out note, "View All Entries" link | ✅ `phonebook.blade.php` + `PhonebookController` |
| `upload.php` | Dropzone, Cropper.js, multi-photo, caption, progress bar | ✅ `upload.blade.php` + `UploadController` |
| `register.php` | Landing page, hero content from DB, theme switcher | ✅ `auth/register.blade.php` + `RegisteredUserController` |
| `profile.php` | Own profile + view other users (?id=N), tabs | ✅ `profile/edit.blade.php` + `wedding/profile.blade.php` |
| `logout.php` | Session destroy + redirect | ✅ Via Breeze `POST /logout` |
| `do-login.php` | POST-only login handler | N/A — superseded by Breeze |

---

## Admin Pages

| File | Legacy Path | Port Status |
|------|-------------|-------------|
| `dash/dash.php` | Admin dashboard | ✅ `admin/dashboard.blade.php` + `DashboardController` |
| `dash/templates.php` | Template management | ✅ `admin/themes.blade.php` + `ThemeController` |
| `admin/themes.php` | Theme switcher UI | ✅ Part of `ThemeController` |
| `admin/theme-settings.php` | Custom color editor | ✅ Part of `ThemeController` |
| `dash/contest.php` | Contest CRUD | ✅ `admin/contests.blade.php` |
| `dash/address-book.php` | Address book admin | ✅ `admin/phonebook.blade.php` |
| `dash/admin-photos.php` | Photo management | ✅ `admin/photos.blade.php` |
| `dash/comments.php` | Comment moderation | ✅ `admin/comments.blade.php` |
| `dash/users-api.php` | User CRUD | ✅ `admin/users.blade.php` |

---

## API Endpoints

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
| `api/phonebook-list.php` | `GET /api/phonebook-list` | ✅ Fixed |
| `api/favorites.php` | `GET /profile/favorites` | ✅ |
| `api/favorite.php` | `POST /api/photo/{id}/favorite` | ✅ |
| `api/rating.php` | `POST /api/photo/{id}/rate` | ✅ |
| `api/contest-entry.php` | `POST /api/photo/{id}/enter-contest` | ✅ |
| `api/like.php` | `POST /api/photo/{id}/like` | ✅ |
| `api/my-comments.php` | `GET /profile/comments` | ✅ |

---

## Registration Fields (All Preserved)

From `api/register.php` — all present in `RegisteredUserController`:
- `firstname`, `lastname`, `email`, `username`, `password`
- `connection`, `core_group`, `specific_relationship`
- `address`, `city`, `state`, `zip`, `phone`, `mobile`, `phone_email`
- Auto-creates `address_book` entry on registration

---

## Theme System

- `ThemeService` with 5 presets: Fortune Gold, Blush Romance, Sage Garden, Navy & Cream, Plum & Gold
- Applied globally via `AppServiceProvider` view composer
- Custom color editor in admin themes page
- Live preview via CSS custom properties
- `theme_settings` DB table shared with old site

## Upload Workflow

1. User selects photos (up to 20, client-side validation)
2. POST to `/upload` with `multipart/form-data`
3. Server: validate (image type), GD resize to thumb (400px) + print (2000px), WebP convert
4. Store: `storage/app/public/` with 3 versions
5. DB insert: `photos` table
6. rclone trigger: NOT ported (old site has async B2 backup queue — low priority)

## Key Gaps Summary

1. **Mail config** — SMTP credentials needed in `.env` (non-breaking: registration works, emails just don't send)
2. **rclone + Telegram** — Old site has async B2 backup + Telegram notifications (informational, not breaking)
3. **Page Manager** — Old `dash.php` had a full `site_pages` editor. Currently admin settings has hero fields only.
4. **E2E tests** — 2 pre-existing timeout failures (low priority)

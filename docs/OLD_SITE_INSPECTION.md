# Old Site Inspection Report
## Source: /www/wwwroot/nickandollie.com/
## Generated: 2026-06-14

---

## Public Pages (root .php files)

| File | Features | Port Status |
|------|----------|-------------|
| `index.php` | Hero landing, theme switcher (?theme=), API routing (/api/login.php, /api/register.php), CSP headers, session config | ❌ Blade `welcome.blade.php` exists but hero is static — needs DB hero content |
| `gallery.php` | Photo grid JS-driven, "Upload" button for logged-in users | ✅ Wired (kanban done) |
| `photo.php` | Lightbox, favorite toggle, star rating (1-5), contest entry select, comments, likes count, back nav | ❌ Not yet in Laravel |
| `contests.php` | Contest cards with top-3 thumbnails, status badges (active/closed/draft) | ❌ Blade `contest.blade.php` exists — wired? |
| `contest.php` | Entry grid with lightbox, vote buttons, prev/next nav | ❌ Blade `contest-show.blade.php` exists |
| `enter-contest.php` | NOW REDIRECTS to gallery.php (contest entry only from photo.php) | N/A |
| `phonebook.php` | Grouped contact list, opt-out note, "View All Entries" link | ✅ Wired (kanban done) |
| `upload.php` | Dropzone, Cropper.js, multi-photo (up to 5), caption, progress bar, success overlay | ❌ Blade `upload.blade.php` exists but not wired |
| `register.php` | Landing page, hero content from DB (`site_pages.page_key='index_hero'`), theme switcher | ❌ Not wired — hero needs site_pages lookup |
| `profile.php` | Own profile + view other users (?id=N), tabs: My Photos, Favorites, Comments, Settings, Upload Photos | ❌ Blade `profile/` dir exists but not wired |
| `logout.php` | Session destroy + redirect | ✅ Via Breeze |
| `do-login.php` | POST-only login handler (redirect pattern, superseded by API) | N/A |

---

## Admin Pages (/admin/)

| File | Features | Port Status |
|------|----------|-------------|
| `themes.php` | Theme switcher UI — list themes, set current, preview links | ❌ Laravel `ThemeController` exists but no Blade view at `/admin/themes` |
| `theme-settings.php` | Edit primary/secondary/accent/background/text via DB | ❌ Laravel `ThemeController` has `update()` but view at `/admin/themes` is a gallery view, not settings form |
| `template-settings.php` | Secondary settings page (likely font/layout overrides) | ❌ Not ported |
| `contest.php` | Create/edit contest (title, description, icon, status, dates) | ❌ Admin `ContestController` has CRUD but need view |
| `contests.php` | List all contests with entry counts | ❌ Admin `Contests` view exists but not tested |
| `contest-vote.php` | Admin view of votes per contest | ❌ Not ported |
| `contest-votes.php` | Vote data table | ❌ Not ported |
| `contest-entry.php` | Approve/reject entries | ❌ Not ported |
| `gallery.php` | Admin photo grid with delete | ❌ Not ported |
| `photo.php` | Admin photo detail | ❌ Not ported |
| `admin-photos.php` | Bulk photo management | ❌ Not ported |
| `upload.php` | Admin upload (bypass user ownership) | ❌ Not ported |
| `users-api.php` | User management API | ❌ Not ported |
| `address-book.php` | Full address book admin (CRUD contacts) | ❌ Not ported |
| `phonebook-list.php` | Admin phonebook list | ❌ Not ported |
| `comments.php` | Comment moderation | ❌ Not ported |
| `my-comments.php` | User comment history | ❌ Not ported |
| `favorites.php` | User favorites | ❌ Not ported |
| `favorite.php` | Toggle favorite API | ❌ Not ported |
| `rating.php` | Rate photo API | ❌ Not ported |
| `like.php` | Like photo API | ❌ Not ported |
| `register.php` | Manual user creation | ❌ Not ported |
| `profile.php` | Edit user profile | ❌ Not ported |
| `login.php` | Admin login page | ❌ Not ported |
| `theme-preview.php` | Theme preview iframe | ❌ Not ported |

---

## Dashboard (/dash/)

| File | Features | Port Status |
|------|----------|-------------|
| `dash.php` | Main admin dashboard — stats (users, photos, contests), contest table with JS | ✅ Blade `dashboard.blade.php` exists — wired to `DashboardController` |
| `templates.php` | Template card gallery | ❌ Not ported |
| `template-card.php` | Template card partial | ❌ Not ported |
| `users-api.php` | User CRUD JSON API | ❌ Not ported |
| `logs-api.php` | System logs | ❌ Not ported |
| `site-pages-api.php` | Static page content API | ❌ Not ported |

---

## API Endpoints (/api/)

| File | Method | Features | Port Status |
|------|--------|----------|-------------|
| `login.php` | POST | Rate limit (10/min), email-or-username, sets session | ✅ Laravel Breeze |
| `register.php` | POST | Rate limit (5/min), 17 fields (names, email, username, password, connection, core_group, relationship, address/city/state/zip/phone/mobile), lookup FK resolution | ⚠️ Breeze registration — missing legacy fields (connection, core_group, relationship, address) |
| `gallery.php` | GET | Paginated photo grid (limit, offset), includes uploader info | ✅ `GalleryController@index` |
| `photo.php` | GET | Single photo + liked/favorited/rating state for current user | ❌ Not ported |
| `upload.php` | POST | Multi-photo upload, WebP conversion, EXIF strip, caption, DB insert, rclone trigger | ❌ `UploadController` exists but not tested end-to-end |
| `contests.php` | GET | List contests with entry counts, top-3 thumbnails | ❌ `ContestController` exists but view not verified |
| `contest.php` | GET/POST | Single contest + entries, enter photo | ❌ Not fully wired |
| `contest-vote.php` | GET | Vote counts per contest | ❌ Not ported |
| `contest-votes.php` | GET | All votes data | ❌ Not ported |
| `contest-entry.php` | POST | Enter photo in contest | ❌ Not ported |
| `favorite.php` | POST | Toggle favorite | ❌ Not ported |
| `favorites.php` | GET | User favorites | ❌ Not ported |
| `like.php` | POST | Toggle like | ❌ Not ported |
| `rating.php` | POST | Set star rating (1-5) | ❌ Not ported |
| `comments.php` | GET/POST | List/add comments | ❌ Not ported |
| `my-comments.php` | GET | User comment history | ❌ Not ported |
| `profile.php` | GET/PUT | View/edit profile | ❌ Not ported |
| `phonebook-list.php` | GET | Public phonebook (name, phone, email, address per group) | ✅ Wired |
| `address-book.php` | GET | Full address book (includes non-public fields) | ❌ Not ported |
| `theme-settings.php` | GET | Current theme settings from DB | ❌ Not ported |
| `template-settings.php` | GET | Extended template settings | ❌ Not ported |
| `theme-preview.php` | GET | Theme preview data | ❌ Not ported |

---

## Database Tables (from audit docs)

| Table | Purpose | Status |
|-------|---------|--------|
| `users` | guests, admins — has legacy enum cols + FK cols | ⚠️ Laravel `User` model exists |
| `photos` | uploaded photos, WebP filenames (original/thumb/print) | ❌ No `Photo` model |
| `contests` | contest definitions | ❌ No `Contest` model |
| `contest_entries` | photo-contest junction | ❌ No model |
| `votes` | star ratings | ❌ No model |
| `favorites` | user-favorite-photo | ❌ No model |
| `comments` | photo comments | ❌ No model |
| `lookup_options` | connection/core_group dropdowns | ❌ No model |
| `address_book` | phonebook entries | ❌ No model |
| `theme_settings` | CSS color overrides | ✅ Wired via CSS vars |
| `site_pages` | hero content, static text | ❌ No model |

---

## Registration Fields (Legacy — must preserve)

From `api/register.php` — all required:
- `firstname`, `lastname`, `email`, `username`, `password`
- `connection` (dropdown: how they know the couple)
- `core_group` (dropdown: Bride side / Groom side / Both / Other)
- `specific_relationship` (free text: e.g. "Aunt of Bride")
- `address`, `city`, `state`, `zip`, `phone`, `mobile`, `phone_email`

Connection/core_group values come from `lookup_options` table.
Legacy rows have NULL FKs and valid enum strings — queries must COALESCE.

---

## Theme System

- Current: `theme_settings` DB row with columns: `primary`, `secondary`, `accent`, `background`, `text`
- Applied as CSS custom properties in `app.blade.php`
- 5 built-in theme presets (Fortune Gold, Blush Romance, Sage Garden, Navy & Cream, Plum & Gold) defined in `inc/theme-manager.php`
- Theme switcher sets DB row, applies immediately (no page reload)
- Admin `/admin/themes.php` lets admin pick and preview themes
- Admin `/admin/theme-settings.php` lets admin edit the color values directly

Laravel status:
- `ThemeController` exists but only has `index()` (renders themes gallery) and `update()` (saves colors)
- No dedicated settings form view — `/admin/themes` renders a gallery, not an editor
- Theme presets not yet ported

---

## Upload Workflow

1. User selects up to 5 photos (Cropper.js crop optional per photo)
2. Client-side resize to max 2048px before upload
3. POST to `/api/upload.php` with `multipart/form-data`
4. Server: validate (10MB max, image type), convert to WebP, strip EXIF
5. Store: `/images/` dir with 3 versions — original, thumb (400px), print (2048px)
6. DB insert: `photos` table (filename, thumb_filename, print_filename, caption, uploader_id, likes=0)
7. rclone trigger: async copy of originals to offsite B2 storage
8. Telegram notification to admin bot on success

Laravel status:
- `UploadController` exists with `__invoke()` (GET shows form) and unnamed method for POST
- Needs verification that multi-photo, WebP conversion, rclone trigger, Telegram notification all work

---

## Key Gaps Summary

1. **Models**: Photo, Contest, ContestEntry, Vote, Favorite, Comment, LookupOption, AddressBook, SitePage — all missing
2. **API Controllers**: Most old API endpoints have no Laravel equivalent
3. **Registration**: Missing legacy fields (connection, core_group, relationship, address)
4. **Photo detail page** (`photo.php`): likes, favorites, ratings, comments, contest entry — not ported
5. **Contest pages**: voting, entry management, admin views — not ported
6. **Admin theme settings**: color editor form missing, presets not ported
7. **Dashboard**: only basic stats — full admin dashboard (contest management, user management, logs) not ported
8. **Profile page**: user tabs (photos, favorites, comments) not ported
9. **rclone + Telegram**: configured in old site — Laravel .env needs these values
10. **site_pages** hero content: `index_hero` key from `site_pages` table not wired to Blade home page
# new.nickandollie.com site

Laravel rebuild for the Nick & Ollie Fortune wedding site.

## Ported from legacy PHP
- Home / landing page with DB-backed hero content and flower strip
- Gallery with paginated photo grid and pagination
- Photo detail with like, favorite, rate, comment, and contest entry
- Upload with dropzone, cropper, multi-file support, and progress bar
- Contests list and detail pages with lightbox and voting
- Phonebook with search, group filter, and contact cards
- Registration with all legacy fields (connection, core_group, relationship)
- Login/logout via Breeze (email or username)
- Full profile page with account settings, password, favorites, uploads, votes, comments
- Admin dashboard with stats, recent activity, and quick links
- Admin theme management with presets and custom colors
- Admin contest CRUD
- Admin phonebook CRUD
- Admin site settings
- API endpoints for all photo/interaction features

## Design
- Mobile-first layout preserved in shared Blade layout
- Top nav and footer in `resources/views/layouts/navigation.blade.php`
- Wedding palette: warm browns, cream, gold accents
- Filipino cultural elements: flower icons, relationship dropdowns, yugal symbol
- DESIGN.md in legacy repo documents the full design system

## Mail Settings (pending migration)
Legacy `inc/mail.php` uses PHPMailer with Gmail SMTP. Credentials in old `.env`:
- SMTP_HOST, SMTP_PORT, SMTP_USERNAME, SMTP_PASSWORD
- SMTP_ENCRYPTION, SMTP_FROM_EMAIL, SMTP_FROM_NAME

## Resumable work
See `docs/MIGRATION_STATUS.md` for detailed page-by-page status and pending items.

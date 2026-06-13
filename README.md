# new.nickandollie.com site

Laravel rebuild for the Nick & Ollie Fortune wedding site.

## Ported from legacy PHP
- Home / landing page now includes the legacy page inventory and migration status cards
- Shared hero copy now reflects the old site’s inventory and next-step blocks
- Gallery, contest, upload, and phonebook pages now have expanded Blade shells
- Admin shells for dashboard, settings, phonebook, contests, themes remain wired
- Shared layout now provides mobile-first nav/footer and keeps the wedding palette
- Resumable migration notes are kept here so the rebuild can continue safely

## Legacy page inventory discovered
- Home / landing
- Gallery
- Upload
- Contest
- Phonebook
- Register / auth
- Admin dashboard / themes / settings
- Legacy mail helper and notifier flows
- Theme switching and DB-backed hero content

## UI notes
- Mobile-first layout preserved in the shared Blade layout
- Top nav and footer now live in `resources/views/layouts/app.blade.php`
- Hero/content cards use the same wedding palette and compact phone-first spacing
- Mail contact placeholder preserved for later SMTP migration

## Pending migration items
- Legacy PHP mail settings from `inc/mail.php` still need Laravel mail config mapping:
  - `SMTP_HOST`
  - `SMTP_PORT`
  - `SMTP_USERNAME`
  - `SMTP_PASSWORD`
  - `SMTP_ENCRYPTION`
  - `SMTP_FROM_EMAIL`
  - `SMTP_FROM_NAME`
- Legacy theme/content database-backed hero content still needs full Laravel data binding
- Gallery, contest, upload, and phonebook pages still need controller/data parity with legacy PHP
- Phonebook lookup label fallbacks and admin CRUD still need wiring

## Resumable work
- Continue converting legacy page content into Blade partials/components
- Add controller-backed data loading for pages
- Migrate legacy theme selector into Laravel config or DB-backed settings
- Keep docs updated as pages are ported
- Update task board entries after each page chunk so the rebuild stays resumable

## Legacy page inventory
- Home / landing
- Gallery
- Upload
- Contest
- Phonebook
- Register / auth
- Admin dashboard / themes / settings
- Legacy mail helper and notifier flows

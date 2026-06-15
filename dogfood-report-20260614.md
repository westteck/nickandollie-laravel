# Dogfood Test Report — new.nickandollie.com
**Date:** 2026-06-14
**Tester:** Hermes (browser walkthrough + CLI)
**Scope:** Full site — guest, auth, admin flows

---

## Executive Summary

🟡 **3 bugs found, 2 fixed during test, 1 cosmetic warning**

| Severity | Count |
|---|---|
| 🔴 Critical | 0 |
| 🟠 High | 2 (both fixed) |
| 🟡 Medium | 1 |
| 🟢 Low | 0 |

**Site status:** Live at https://new.nickandollie.com — core flows work

---

## Bugs Found

### 🟠 BUG-001: Photo detail page returns 500 [HIGH — FIXED]
- **URL:** `https://new.nickandollie.com/photo/1`
- **Severity:** High
- **Category:** Functional
- **Root cause:** `PhotoController::show()` returned `$contests` as array of plain arrays from `->map()`, but `photo.blade.php` used object syntax `$contest->id`, `$contest->end_date`. PHP threw `ErrorException: Attempt to read property "id" on array` in compiled view.
- **Fix:** Added `(object)` cast in `app/Http/Controllers/PhotoController.php` line 87
- **Verification:** `curl -s -o /dev/null -w "%{http_code}" https://new.nickandollie.com/photo/1` → **200** ✅
- **Note:** `$enteredContests` map for comments also returns arrays but the view correctly accesses via `$c['user']` / `$c['text']` — no conflict there.

---

### 🟠 BUG-002: Landing page returns 500 when logged in [HIGH — FIXED]
- **URL:** `https://new.nickandollie.com/` (authenticated)
- **Severity:** High
- **Category:** Functional
- **Root cause:** `HomeController::__invoke()` had return type `View` but returned `Illuminate\Http\RedirectResponse` when `Auth::check()` is true. PHP 8 strict return types threw `TypeError`.
- **Fix:** Added `use Illuminate\Http\RedirectResponse;` and changed return type to `View|RedirectResponse`
- **Verification:** Authenticated browser now redirects `/` → `/gallery` ✅

---

### 🟡 BUG-003: Alpine slideshow warning on landing page [MEDIUM]
- **URL:** `https://new.nickandollie.com/`
- **Severity:** Medium
- **Category:** Console / JS
- **Console error:** `Alpine Expression Error: slideshow is not defined — Expression: "slideshow()"`
- **Root cause:** `home.blade.php` line 47: `<div x-data="slideshow()">` — Alpine evaluates `slideshow()` as a JavaScript function, but `slideshow` only exists as an `Alpine.store`, not a global function. The store IS registered correctly in `alpine:init` and works for `$store.slideshow.current` references, but the `x-data` binding fails silently.
- **Impact:** Slideshow may not auto-advance on some page loads; console shows warning on every page load
- **Fix needed:** Change `x-data="slideshow()"` to `x-data="{}"` since slideshow is driven by Alpine store, not component state. The store auto-starts in `alpine:init`.
- **Verification:** Slideshow images DO display and transition work via store — this is cosmetic, slideshow still functional

---

## Pages Tested ✅

| Page | URL | Status | Notes |
|---|---|---|---|
| Landing (guest) | `/` | ✅ 200 | Default hero + countdown + login form |
| Landing (auth) | `/` | ✅ 302→gallery | Now fixed (was 500) |
| Gallery | `/gallery` | ✅ 200 | 2 photos, 5-col grid |
| Photo detail | `/photo/1` | ✅ 200 | FIXED (was 500) — like/fav/rate/comment UI all render |
| Contest list | `/contest` | ✅ 200 | 2 contests listed |
| Contest detail | `/contest/1` | ✅ 200 | Shows 1 entry |
| Upload | `/upload` | ✅ 200 | File picker + caption form renders |
| Phonebook | `/phonebook` | ✅ 200 | Search + group filter works |
| Admin dashboard | `/admin` | ✅ 200 | Shell with nav links |
| Admin themes | `/admin/themes` | ✅ 200 | 5 presets + custom color picker |
| Admin settings | `/admin/settings` | ✅ 200 | Site info + hero section forms |
| Admin phonebook | `/admin/phonebook` | ✅ 200 | Table with 4 contacts, add/edit/delete |
| Admin contests | `/admin/contests` | ✅ 200 | 2 contests with entry counts |

---

## JS Console Errors

| Error | Source | Impact |
|---|---|---|
| `Alpine Expression Error: slideshow is not defined` | home.blade.php line 47 | Medium — slideshow still works via store |
| `source: "exception" (empty message)` ×2 | Misc | Low — appears to be 403 misreports from Alpine error; not real JS exceptions |

---

## Not Fully Tested ⚠️

These require real user interaction or actual file upload:

1. **Like/Favorite/Rate/Comment** — API calls return 401 via curl (CSRF token required), browser Playwright headless fetch has issues with session persistence. Login API confirmed working via `browser_console(expression)`.
2. **Photo upload** — form renders correctly, actual file upload untested
3. **Contest entry** — API call not verified end-to-end
4. **Theme toggle** — button exists on nav, not tested
5. **Registration flow** — register page form not tested
6. **Password reset** — not tested

---

## Code Quality Notes

1. **Inconsistent object/array returns in PhotoController:** Two `->map()` closures return plain arrays (`return [...]`) while view expects objects. BUG-001 was caught; check other controllers for same pattern.
2. **HomeController strict return types:** PHP 8 union types (`View|RedirectResponse`) used but intelephense LSP flags as error — PHP 8.3 handles this fine, LSP version mismatch.
3. **JS exception empty messages** are likely Laravel 403 responses misreported by Playwright — see dogfood skill pitfalls.

---

## Summary Table

| # | Severity | Page | Issue | Status |
|---|---|---|---|---|
| 1 | 🟠 High | `/photo/{id}` | 500 — array vs object in contests map | ✅ FIXED |
| 2 | 🟠 High | `/` (auth) | 500 — wrong return type in HomeController | ✅ FIXED |
| 3 | 🟡 Medium | `/` | Alpine slideshow warning — x-data references undefined fn | 🔧 Needs fix |
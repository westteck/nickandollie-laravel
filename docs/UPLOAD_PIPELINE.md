# Upload Pipeline & Offsite Storage Plan

## Overview

Photos uploaded through the `/upload` page go through a multi-stage pipeline: client-side resize → HTTP POST → Laravel server processing → local storage → offsite backup via rclone.

---

## Stage 1: Client-Side Resize (JavaScript)

Before any upload, selected images are processed in-browser:

1. User selects photos via the tap-to-select dropzone (up to 20 photos)
2. Each photo is loaded into an `<img>` element via `FileReader`
3. Canvas API resizes the image to a maximum of **2048px** on the longest edge, preserving EXIF orientation
4. Cropper.js modal appears for each photo:
   - **4:3 aspect ratio** enforced
   - User can crop, zoom, and adjust the framing
   - "Skip" button bypasses cropping (photo still gets resized)
   - "Crop & Continue" advances to the next photo
5. Final cropped image is exported as a high-quality JPEG blob (92% quality)

### Key Client-Side Parameters

| Parameter | Value |
|-----------|-------|
| Max dimension | 2048px |
| Output format | JPEG (image/jpeg) |
| Quality | 92% |
| Crop aspect ratio | 4:3 |
| Max photos per batch | 20 |
| EXIF orientation | Preserved via CSS transform in Cropper; actual fix done server-side |

---

## Stage 2: HTTP Upload (XHR)

1. All processed photos are bundled into a single `multipart/form-data` POST to `/upload`
2. The form also includes the shared `caption` field (applied to all photos in the batch)
3. `X-CSRF-TOKEN` header is sent with Laravel's CSRF token from `<meta name="csrf-token">`
4. `XMLHttpRequest` tracks per-upload progress events:
   - `upload.onprogress` updates a progress bar (0–100%)
   - Progress label shows "Uploading photo N of M"
5. On completion, the server response is parsed:
   - **2xx**: Show success overlay with "View Gallery" and "Upload More" buttons
   - **Non-2xx**: Show error toast with server error message

---

## Stage 3: Laravel Server Processing (UploadController)

### Request Validation

```php
'photos'   => 'required',
'photos.*' => 'file|max:51200',  // 50MB max per file (server-side cap)
'caption'  => 'string|max:255',
```

Allowed extensions: `jpg`, `jpeg`, `png`, `webp`.

### Processing Steps

1. **GD image verification** — each file is loaded via `imagecreatefromjpeg` / `imagecreatefrompng` / `imagecreatefromwebp` to confirm it's a real image
2. **Batch naming** — photos are assigned sequential `photo_number`s (e.g., `001-0001`, `001-0002`). Every 1000 photos increments the batch prefix (`001`, `002`, …)
3. **Original file** — saved to `storage/app/public/originals/{batch}-{seq}.{ext}`
4. **Thumbnail (400px)** — `processImage()` creates a WebP copy at max 400px width, 90% quality, saved to `storage/app/public/thumbs/{batch}-{seq}.webp`
5. **Print (2000px)** — `processImage()` creates a WebP copy at max 2000px width, 80% quality, saved to `storage/app/public/print/{batch}-{seq}.webp`
6. **EXIF orientation fix** — JPEG uploads with orientation tag > 1 are rotated using `imagerotate()` before conversion
7. **Database insert** — row in `photos` table:
   - `filename`, `thumb_filename`, `print_filename`
   - `original_filename` (client-side original name)
   - `uploader_id` (from `auth()->id()`)
   - `caption` (from `$request->input('caption', '')`)
   - `photo_number`, `likes = 0`, `uploaded_at = now()`

### Storage Layout

```
storage/app/public/
├── originals/   ← original uploaded files (JPG, PNG, WebP)
├── thumbs/      ← 400px WebP versions
└── print/       ← 2000px WebP versions
```

---

## Stage 4: Offsite Backup via rclone

### Current Setup

The server has rclone installed and configured at `~/.rclone.conf`. A cron job runs every 2 minutes to sync new originals to a B2 (Backblaze) or S3-compatible bucket.

**Script location:** `/www/wwwroot/new.nickandollie.com/scripts/rclone-copyto.sh`

### rclone Command (typical)

```bash
rclone copy \
  /www/wwwroot/new.nickandollie.com/site/storage/app/public/originals/ \
  b2:bucket-name/originals/ \
  --files-from /tmp/rclone-files-list.txt \
  --transfers 4 \
  --checkers 8 \
  --exclude "*.tmp"
```

> **Note:** The current implementation copies from `storage/app/public/originals/` only. Thumbs and print versions are not offsite-backed up (they can be regenerated from originals).

### rclone Configuration

The remote is defined in `~/.rclone.conf` on the server (not committed to the repo). Example:

```ini
[b2]
type = b2
account = your_key_id
key = your_application_key
bucket = your-bucket-name
endpoint =
```

### Cron Job

The sync script runs every 2 minutes via crontab:

```cron
*/2 * * * * /www/wwwroot/new.nickandollie.com/scripts/rclone-copyto.sh >> /var/log/rclone-copy.log 2>&1
```

To check if the cron job is active:
```bash
crontab -l | grep rclone
```

### Monitoring

- Log output: `/var/log/rclone-copy.log`
- rclone stats are printed to stdout on each run
- Failures should be monitored via the cron job's email output or a log watcher

---

## Stage 5: Telegram Notifications (Pending)

The old site sends a Telegram message to an admin bot on successful upload. This is **not yet configured** in the Laravel app.

### Implementation Required

1. Add to `.env`:
   ```
   TELEGRAM_BOT_TOKEN=your_bot_token
   TELEGRAM_CHAT_ID=your_chat_id
   ```

2. Create a Laravel notification or service class:
   ```php
   // app/Services/TelegramNotifier.php
   $token = env('TELEGRAM_BOT_TOKEN');
   $chatId = env('TELEGRAM_CHAT_ID');
   $message = "📸 {$count} photo(s) uploaded by " . auth()->user()->name;
   file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?" . http_build_query([
       'chat_id' => $chatId,
       'text' => $message,
   ]));
   ```

3. Call `TelegramNotifier::send(...)` in `UploadController::handleUpload()` after the DB insert loop:
   ```php
   if (count($uploaded) > 0) {
       app(\App\Services\TelegramNotifier::class)->notify(count($uploaded), auth()->user()->name);
   }
   ```

---

## Full Flow Diagram

```
[User selects photos]
       ↓
[JS: resize to max 2048px]
       ↓
[JS: Cropper.js modal per photo — crop or skip]
       ↓
[XHR POST /upload — multipart/form-data + CSRF token]
       ↓
[Laravel validates + GD image check]
       ↓
[Laravel generates batch filenames]
       ↓
[Original file → storage/app/public/originals/]
       ↓
[GD: create thumb (400px WebP) → thumbs/]
       ↓
[GD: create print (2000px WebP) → print/]
       ↓
[DB insert: photos table with caption]
       ↓
[Laravel redirect to /gallery]
       ↓
[rclone cron (every 2 min): copies originals/ → B2]
       ↓
[Telegram notification (to be implemented)]
```

---

## Environment Variables Required

| Variable | Purpose | Status |
|----------|---------|--------|
| `APP_URL` | Base URL for routes | ✅ Set |
| `DB_*` | Database connection | ✅ Set |
| `TELEGRAM_BOT_TOKEN` | Telegram bot token | ❌ Missing |
| `TELEGRAM_CHAT_ID` | Admin chat ID | ❌ Missing |
| `RCLONE_B2_ACCOUNT` | rclone B2 key ID | ❌ In ~/.rclone.conf |
| `RCLONE_B2_KEY` | rclone B2 app key | ❌ In ~/.rclone.conf |

---

## TODO

- [ ] Add Telegram notification after successful upload
- [ ] Implement `Photo` Eloquent model for type-safe access
- [ ] Add a Laravel Job (`SyncOriginalsToB2`) triggered after upload instead of relying on cron
- [ ] Consider backing up thumbs/ and print/ directories as well (incremental storage cost vs. regeneration cost)
- [ ] Add upload rate limiting (per user) to prevent abuse
- [ ] Add virus scanning (ClamAV) on uploaded files before GD processing
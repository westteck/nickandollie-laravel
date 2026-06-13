<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->handleUpload($request);
        }
        return view('upload');
    }

    private function handleUpload(Request $request)
    {
        $request->validate([
            'photos' => 'required',
            'photos.*' => 'file|max:51200',
        ]);

        $files = $request->file('photos');
        if (!is_array($files)) {
            $files = [$files];
        }

        $uploaded = [];
        $failed = [];

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

        // Determine next photo number for batch naming
        $last = DB::table('photos')->select('photo_number')->orderBy('photo_number', 'desc')->first();
        $nextNum = $last ? ($last->photo_number + 1) : 1;

        foreach ($files as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedExts)) {
                $failed[] = $file->getClientOriginalName() . ' (bad ext)';
                continue;
            }

            // Verify it's a real image via GD
            $tmpPath = $file->getRealPath();
            $testImg = $ext === 'png' ? @imagecreatefrompng($tmpPath)
                : ($ext === 'webp' ? @imagecreatefromwebp($tmpPath)
                : @imagecreatefromjpeg($tmpPath));
            if ($testImg === false) {
                $failed[] = $file->getClientOriginalName() . ' (not image)';
                continue;
            }
            imagedestroy($testImg);
            $batch = str_pad((int)(($nextNum - 1) / 1000) + 1, 3, '0', STR_PAD_LEFT);
            $seq = str_pad((($nextNum - 1) % 1000) + 1, 4, '0', STR_PAD_LEFT);
            $baseName = "{$batch}-{$seq}";

            $originalName = $baseName . '.' . $file->getClientOriginalExtension();
            $thumbName = $baseName . '.webp';
            $printName = $baseName . '.webp';

            $originalPath = storage_path("app/public/originals/{$originalName}");
            $thumbPath = storage_path("app/public/thumbs/{$thumbName}");
            $printPath = storage_path("app/public/print/{$printName}");

            // Save original
            $file->move(dirname($originalPath), basename($originalPath));

            // Process thumb (400px, quality 90)
            if (!$this->processImage($originalPath, $thumbPath, 400, 90)) {
                $failed[] = $file->getClientOriginalName();
                continue;
            }

            // Process print (2000px, quality 80)
            if (!$this->processImage($originalPath, $printPath, 2000, 80)) {
                $failed[] = $file->getClientOriginalName();
                continue;
            }

            // Insert DB record
            DB::table('photos')->insert([
                'filename' => $originalName,
                'original_filename' => $file->getClientOriginalName(),
                'thumb_filename' => $thumbName,
                'print_filename' => $printName,
                'uploader_id' => auth()->id(),
                'caption' => '',
                'photo_number' => $nextNum,
                'likes' => 0,
                'uploaded_at' => now(),
            ]);

            $uploaded[] = $baseName;
            $nextNum++;
        }

        return redirect()->route('gallery')->with('status', count($uploaded) . ' photos uploaded' . (count($failed) ? ', ' . count($failed) . ' failed' : ''));
    }

    private function processImage(string $srcPath, string $dstPath, int $maxWidth, int $quality): bool
    {
        $ext = strtolower(pathinfo($srcPath, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'png': $src = @imagecreatefrompng($srcPath); break;
            case 'webp': $src = @imagecreatefromwebp($srcPath); break;
            default: $src = @imagecreatefromjpeg($srcPath); break;
        }

        if ($src === false) return false;

        // EXIF orientation for JPEG
        if (($ext === 'jpg' || $ext === 'jpeg') && function_exists('exif_read_data')) {
            $exif = @exif_read_data($srcPath);
            if ($exif && !empty($exif['Orientation']) && $exif['Orientation'] > 1) {
                $src = $this->fixOrientation($src, (int)$exif['Orientation']);
            }
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        if ($srcW > $maxWidth) {
            $dstW = $maxWidth;
            $dstH = (int) round($srcH * ($maxWidth / $srcW));
            $dst = imagecreatetruecolor($dstW, $dstH);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
        } else {
            $dst = $src;
        }

        $outExt = strtolower(pathinfo($dstPath, PATHINFO_EXTENSION));
        $result = $outExt === 'png' ? imagepng($dst, $dstPath, 9) : imagewebp($dst, $dstPath, $quality);

        if ($dst !== $src) imagedestroy($dst);
        imagedestroy($src);

        return $result;
    }

    private function fixOrientation($image, int $orientation)
    {
        if (!$image || !function_exists('imagerotate')) return $image;
        switch ($orientation) {
            case 3: $image = imagerotate($image, 180, 0); break;
            case 6: $image = imagerotate($image, -90, 0); break;
            case 8: $image = imagerotate($image, 90, 0); break;
        }
        return $image;
    }
}

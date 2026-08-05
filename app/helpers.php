<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('upload_disk')) {
    /**
     * Nama disk penyimpanan untuk seluruh fitur upload.
     * Mengikuti FILESYSTEM_DISK: 's3' -> S3, selain itu -> disk 'public' lokal.
     */
    function upload_disk(): string
    {
        return config('filesystems.uploads', 'public');
    }
}

if (!function_exists('file_url')) {
    /**
     * URL akses sebuah file pada disk upload aktif.
     * S3 memakai presigned (temporary) URL, lokal memakai URL publik biasa.
     */
    function file_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $disk = upload_disk();

        if ($disk === 's3') {
            return Storage::disk($disk)->temporaryUrl(
                $path,
                now()->addMinutes((int) config('filesystems.temporary_url_expiry', 30))
            );
        }

        return Storage::disk($disk)->url($path);
    }
}

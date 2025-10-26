<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Intervention\Image\Facades\Image; // Disabled untuk kompatibilitas

class FileUploadService
{
    // MIME types yang diizinkan untuk gambar
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp'
    ];

    // Ekstensi file yang diizinkan
    private const ALLOWED_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
        'bmp'
    ];

    // Maksimal ukuran file (5MB)
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    public function __construct()
    {
        //
    }

    /**
     * Upload dan validasi file gambar dengan keamanan tinggi
     */
    public function uploadImage(UploadedFile $file, string $directory = 'ticket-attachments'): array
    {
        // 1. Validasi dasar
        $this->validateFile($file);

        // 2. Generate nama file yang aman
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = $this->generateSecureFilename($extension);
        $filePath = $directory . '/' . $filename;

        // 3. Simpan file dengan validasi tambahan
        $storedPath = $file->storeAs($directory, $filename, 'public');

        // 4. Validasi file yang sudah disimpan
        $this->validateStoredFile($storedPath);

        // 5. Generate hash untuk mencegah duplikasi
        $fileHash = hash_file('sha256', storage_path('app/public/' . $storedPath));

        // 6. Resize gambar jika terlalu besar (opsional)
        $this->optimizeImage($storedPath);

        return [
            'original_name' => $originalName,
            'filename' => $filename,
            'file_path' => $storedPath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_hash' => $fileHash,
            'is_image' => true
        ];
    }

    /**
     * Validasi file sebelum upload
     */
    private function validateFile(UploadedFile $file): void
    {
        // Validasi ukuran file
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('Ukuran file terlalu besar. Maksimal 5MB.');
        }

        // Validasi MIME type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new \Exception('Tipe file tidak diizinkan. Hanya file gambar yang diperbolehkan.');
        }

        // Validasi ekstensi file
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception('Ekstensi file tidak diizinkan.');
        }

        // Validasi nama file (mencegah path traversal)
        $originalName = $file->getClientOriginalName();
        if (strpos($originalName, '..') !== false || strpos($originalName, '/') !== false) {
            throw new \Exception('Nama file tidak valid.');
        }

        // Validasi file kosong
        if ($file->getSize() === 0) {
            throw new \Exception('File kosong tidak diperbolehkan.');
        }
    }

    /**
     * Validasi file setelah disimpan
     */
    private function validateStoredFile(string $filePath): void
    {
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            throw new \Exception('File gagal disimpan.');
        }

        // Validasi bahwa file adalah gambar yang valid
        $imageInfo = getimagesize($fullPath);
        if ($imageInfo === false) {
            Storage::disk('public')->delete($filePath);
            throw new \Exception('File bukan gambar yang valid.');
        }

        // Validasi dimensi gambar (maksimal 4000x4000)
        if ($imageInfo[0] > 4000 || $imageInfo[1] > 4000) {
            Storage::disk('public')->delete($filePath);
            throw new \Exception('Dimensi gambar terlalu besar. Maksimal 4000x4000 pixel.');
        }
    }

    /**
     * Generate nama file yang aman
     */
    private function generateSecureFilename(string $extension): string
    {
        $timestamp = now()->format('YmdHis');
        $randomString = Str::random(10);
        return $timestamp . '_' . $randomString . '.' . $extension;
    }

    /**
     * Optimasi gambar (validasi dimensi saja untuk keamanan)
     */
    private function optimizeImage(string $filePath): void
    {
        $fullPath = storage_path('app/public/' . $filePath);

        try {
            // Validasi bahwa file adalah gambar yang valid
            $imageInfo = getimagesize($fullPath);
            if ($imageInfo === false) {
                Storage::disk('public')->delete($filePath);
                throw new \Exception('File bukan gambar yang valid.');
            }

            // Validasi dimensi gambar (maksimal 4000x4000)
            if ($imageInfo[0] > 4000 || $imageInfo[1] > 4000) {
                Storage::disk('public')->delete($filePath);
                throw new \Exception('Dimensi gambar terlalu besar. Maksimal 4000x4000 pixel.');
            }

            // Validasi ukuran file setelah upload
            $fileSize = filesize($fullPath);
            if ($fileSize > self::MAX_FILE_SIZE) {
                Storage::disk('public')->delete($filePath);
                throw new \Exception('Ukuran file terlalu besar setelah upload.');
            }
        } catch (\Exception $e) {
            // Jika gagal validasi, hapus file
            Storage::disk('public')->delete($filePath);
            throw new \Exception('Gagal memproses gambar: ' . $e->getMessage());
        }
    }

    /**
     * Hapus file dari storage
     */
    public function deleteFile(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }
        return false;
    }

    /**
     * Validasi multiple files
     */
    public function validateMultipleFiles(array $files): array
    {
        $errors = [];

        foreach ($files as $index => $file) {
            try {
                $this->validateFile($file);
            } catch (\Exception $e) {
                $errors[] = "File " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return $errors;
    }
}

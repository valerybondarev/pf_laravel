<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Models\File;
use cardinalby\ContentDisposition\ContentDisposition;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;

class FileService
{
    protected array $mimetypes = [
        'image/png' => '.png',
        'image/jpeg' => '.jpg',
        'application/zip' => '.zip',
        'text' => '.txt',
    ];

    public function upload(UploadedFile $uploadedFile, string $disk = null): ?File
    {
        $disk = $this->disk($disk);
        if ($path = Storage::disk($disk)->putFile('upload', $uploadedFile)) {
            $file = new File();
            $file->disk = $disk;
            $file->path = $path;
            $file->original = $uploadedFile->getClientOriginalName();
            $file->ext = $uploadedFile->getClientOriginalExtension();
            $file->mime = $uploadedFile->getClientMimeType();
            $file->save();
            return $file;
        }
        return null;
    }

    public function uploadResponse(Response $response): ?File
    {
        $filename = null;
        if ($contentDisposition = $response->header('Content-Disposition')) {
            $cd = ContentDisposition::parse($contentDisposition);
            $filename = $cd->getFilename();
        }
        $mime = $response->header('Content-Type');
        if (is_null($filename)) {
            $filename = Str::random().($this->mimetypes[$mime] ?? '.tmp');
        }
        return $this->upload($this->responseToFile($response->body(), $filename, $mime));
    }

    protected function responseToFile(string $fileData, ?string $original = null, ?string $mime = null): UploadedFile
    {
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $fileData);

        $tempFileObject = new \Illuminate\Http\File($tempFilePath);
        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            $original ?? $tempFileObject->getFilename(),
            $mime ?? $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        return $file;
    }

    protected function disk($disk): string
    {
        return $disk ?? config('filesystems.default');
    }
}

<?php

namespace App\Http\Admin\Controllers\Tools\Files;

use App\Domain\Application\File\Services\FileManageService;
use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\Requests\Tools\UploadFileRequest;
use App\Http\Admin\Resources\Tools\FileResource;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    public function __construct(private FileManageService $service)
    {
    }

    public function store(UploadFileRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => FileResource::make($this->service->createFromUploadedFile($request->file)),
        ]);
    }
}

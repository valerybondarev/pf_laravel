<?php


namespace App\Http\Admin\Controllers\Tools\Images;


use App\Domain\Application\File\DTO\ImageDTO;
use App\Domain\Application\File\Services\ImageService;
use App\Http\Admin\Requests\Tools\CropImageRequest;
use App\Http\Admin\Requests\Tools\UploadImageRequest;
use App\Http\Admin\Resources\Tools\ImageResource;
use Illuminate\Http\JsonResponse;

class ImageController
{
    public function __construct(
        private ImageService $imageService,
    )
    {
    }

    public function store(UploadImageRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => ImageResource::make($this->imageService->create(ImageDTO::createFromUploadedFile($request->image))),
        ]);
    }

    public function crop(CropImageRequest $request): JsonResponse
    {
        $image = $this->imageService->crop(
            ImageDTO::createFromUploadedFile($request->image),
            $request->width, $request->height, $request->x, $request->y
        );

        return response()->json([
            'success' => true,
            'data'    => ImageResource::make($image),
        ]);
    }
}

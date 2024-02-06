<?php

namespace App\Domain\Application\File\Services;

use App\Base\Exceptions\ExternalServiceException;
use App\Domain\Application\File\DTO\ImageDTO;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Application\File\Repositories\ImageRepository;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image as ImageFacade;
use Intervention\Image\Image as InterventionImage;
use Throwable;

class ImageService
{
    public function __construct(
        private ImageManageService $crudService,
        private ImageEditService $editService,
        private ImageRepository $imageRepository,
    )
    {
    }

    public function create(ImageDTO $imageDTO): Image
    {
        $interventionImage = $this->make($imageDTO->source);
        return $this->save($imageDTO, $interventionImage);
    }

    public function fit(ImageDTO $imageDTO, int $width, int $height): Image
    {
        if ($imageDTO->path) {
            $filename = pathinfo($imageDTO->path, PATHINFO_FILENAME);
            $imageDTO->path = Str::replace($filename, $filename . "_fitted_{$width}_{$height}", $imageDTO->path);

            if ($image = $this->imageRepository->find(['path' => $imageDTO->path])) {
                return $image;
            }
        }

        $interventionImage = $this->make($imageDTO->source);
        $interventionImage = $this->editService->fit($interventionImage, $width, $height);
        return $this->save($imageDTO, $interventionImage);
    }

    public function crop(ImageDTO $imageDTO, int $width, int $height, int $x, int $y): Image
    {
        if ($imageDTO->path) {
            $filename = pathinfo($imageDTO->path, PATHINFO_FILENAME);
            $imageDTO->path = Str::replace($filename, $filename . "_cropped_{$width}_{$height}", $imageDTO->path);

            if ($image = $this->imageRepository->find(['path' => $imageDTO->path])) {
                return $image;
            }
        }

        $interventionImage = $this->make($imageDTO->source);
        $interventionImage = $this->editService->crop($interventionImage, $width, $height, $x, $y);
        return $this->save($imageDTO, $interventionImage);
    }

    public function resize(ImageDTO $imageDTO, int $width, int $height): Image
    {
        if ($imageDTO->path) {
            $filename = pathinfo($imageDTO->path, PATHINFO_FILENAME);
            $imageDTO->path = Str::replace($filename, $filename . "_resized_{$width}_{$height}", $imageDTO->path);

            if ($image = $this->imageRepository->find(['path' => $imageDTO->path])) {
                return $image;
            }
        }

        $interventionImage = $this->make($imageDTO->source);
        $interventionImage = $this->editService->resize($interventionImage, $width, $height);
        return $this->save($imageDTO, $interventionImage);
    }

    public function widen(ImageDTO $imageDTO, int $width): Image
    {
        if ($imageDTO->path) {
            $filename = pathinfo($imageDTO->path, PATHINFO_FILENAME);
            $imageDTO->path = Str::replace($filename, $filename . "_widened_{$width}", $imageDTO->path);

            if ($image = $this->imageRepository->find(['path' => $imageDTO->path])) {
                return $image;
            }
        }

        $interventionImage = $this->make($imageDTO->source);
        $interventionImage = $this->editService->widen($interventionImage, $width);
        return $this->save($imageDTO, $interventionImage);
    }


    public function encode(ImageDTO $imageDTO, string $extension): Image
    {
        if ($imageDTO->path) {
            $imageDTO->path = Str::replace(pathinfo($imageDTO->path, PATHINFO_EXTENSION), $extension, $imageDTO->path);

            if ($image = $this->imageRepository->find(['path' => $imageDTO->path])) {
                return $image;
            }
        }

        $interventionImage = $this->make($imageDTO->source);
        $interventionImage = $this->editService->encode($interventionImage, $extension);
        return $this->save($imageDTO, $interventionImage);
    }

    /**
     * @param $source
     *
     * @return InterventionImage|null
     * @throws ExternalServiceException
     */
    private function make($source): ?InterventionImage
    {
        try {
            return ImageFacade::make($source);
        } catch (Throwable $exception) {
            throw new ExternalServiceException('Image processing failed.', 0, $exception);
        }
    }

    private function storage(string $disk = 'public'): FilesystemAdapter
    {
        return Storage::disk($disk);
    }

    private function save(ImageDTO $imageDTO, InterventionImage $image): Image
    {
        $extension = Str::after($image->mime, 'image/');
        $imageDTO->path = $imageDTO->path ?: $this->generatePath($imageDTO->directory, $extension, $imageDTO->disk);
        $this->storage($imageDTO->disk)->put($imageDTO->path, $image->stream(null, 100));

        return $this->crudService->create([
            'disk'      => $imageDTO->disk,
            'name'      => $imageDTO->name,
            'path'      => $imageDTO->path,
            'extension' => $extension,
            'size'      => $image->filesize(),
        ]);
    }

    private function generatePath(string $directory, string $extension, string $disk = 'public'): string
    {
        do {
            $path = $directory . DIRECTORY_SEPARATOR . Str::random(20) . '.' . $extension;
        } while ($this->storage($disk)->exists($path));
        return $path;
    }
}

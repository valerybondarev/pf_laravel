<?php


namespace App\Http\Admin\Resources\Tools;


use App\Domain\Application\File\Entities\File;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property File $resource
 */
class FileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->resource->id,
            'name' => $this->resource->name,
            'path' => $this->resource->path,
            'url'  => $this->resource->url,
            'size' => $this->resource->size,
        ];
    }
}

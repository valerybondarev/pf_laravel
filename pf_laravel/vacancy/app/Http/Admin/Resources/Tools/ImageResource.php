<?php

namespace App\Http\Admin\Resources\Tools;

use App\Domain\Application\File\Entities\Image;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Image $resource
 */
class ImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->resource->id,
            'name'      => $this->resource->name,
            'size'      => $this->resource->size,
            'extension' => $this->resource->extension,
            'url'       => $this->resource->url,
        ];
    }
}

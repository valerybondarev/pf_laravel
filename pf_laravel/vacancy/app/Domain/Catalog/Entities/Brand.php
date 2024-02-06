<?php

namespace App\Domain\Catalog\Entities;



use App\Base\Models\BaseModel;
use App\Domain\Catalog\Enums\BrandStatus;
use Carbon\Carbon;
use Eloquent;

/**
 * Class Brand
 * @package App\Domain\Catalog\Entities
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 *
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Brand extends BaseModel
{
    protected $table = 'brand';

    public function getTitle()
    {
        return $this->title;
    }

    public function getStatus()
    {
        return BrandStatus::label($this->status);
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d', strtotime($this->created_at)) : null;
    }
}

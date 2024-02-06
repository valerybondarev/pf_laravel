<?php

namespace App\Domain\Catalog\Entities;



use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Catalog\Enums\ProductRequestStatus;
use App\Domain\News\Enums\NewsStatus;
use Carbon\Carbon;
use Eloquent;

/**
 * Class Product
 * @package App\Domain\Catalog\Entities
 * @mixin Eloquent
 *
 * @property int $id
 * @property integer $product_id
 * @property string $name
 * @property string $email
 * @property string $phone
 *
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product $product
 * @property Subcategory $subcategory
 *
 */
class ProductRequest extends BaseModel
{

    protected $table = 'product_request';

    public function getStatus()
    {
        return ProductRequestStatus::label($this->status);
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d', strtotime($this->created_at)) : null;
    }
}

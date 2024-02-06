<?php

namespace App\Domain\Catalog\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Catalog\Enums\CatalogImportJobStatus;
use App\Domain\News\Enums\NewsStatus;
use Carbon\Carbon;
use Eloquent;

/**
 * Class Brand
 * @package App\Domain\Catalog\Entities
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $file_id
 * @property string $report_text
 *
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property File $file
 */
class CatalogImportJob extends BaseModel
{
    protected $table = 'catalog_import_job';

    public function getStatus()
    {
        return CatalogImportJobStatus::label($this->status);
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d j:i', strtotime($this->created_at)) : null;
    }

    public function getReport()
    {
        $reportArray = $this->report_text ? json_decode($this->report_text, true) : [];
        $text = 'Успешно добавлено: ' . ($reportArray['successCreatedCount'] ?? 0) . ' | ';
        $text .= 'Успешно обновлено: ' . ($reportArray['successUpdatedCount'] ?? 0) . ' | ';
        $text .= 'Ошибка: ' . ($reportArray['errorCount'] ?? 0);
        return $text;
    }

    public function getFilename()
    {
        return $this->file ? $this->file->name : '';
    }
}

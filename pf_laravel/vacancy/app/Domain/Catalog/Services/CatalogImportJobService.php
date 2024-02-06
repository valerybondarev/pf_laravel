<?php

namespace App\Domain\Catalog\Services;

use App\Domain\Catalog\Entities\CatalogImportJob;
use App\Domain\Catalog\Enums\CatalogImportJobStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CatalogImportJobService
{
    public function create(array $data): CatalogImportJob
    {
        $catalogImportJob = new CatalogImportJob();
        $catalogImportJob->status = CatalogImportJobStatus::WAIT;
        $catalogImportJob->file_id = Arr::get($data, 'file_id');
        return $this->update($catalogImportJob, $data);
    }

    public function update(Model|CatalogImportJob $model, array $data): CatalogImportJob
    {
        $model->report_text = Arr::get($data, 'report_text');
        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        return $model;
    }
}

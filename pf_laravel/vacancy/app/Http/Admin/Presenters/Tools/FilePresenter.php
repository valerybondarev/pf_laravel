<?php


namespace App\Http\Admin\Presenters\Tools;


use App\Base\Presenters\AbstractPresenter;
use App\Domain\Application\File\Entities\File;

/**
 * Class FilePresenter
 * @package App\Http\Admin\Presenters\Tools
 *
 * @property File $model
 */
class FilePresenter extends AbstractPresenter
{
    public function toArray(): array
    {
        return [
            'id' => $this->model->id,
            'name' => $this->model->name,
            'path' => $this->model->path,
            'url' => $this->model->url,
            'size' => $this->model->size
        ];
    }
}

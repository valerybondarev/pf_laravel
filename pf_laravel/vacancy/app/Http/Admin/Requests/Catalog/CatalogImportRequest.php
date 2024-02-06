<?php


namespace App\Http\Admin\Requests\Catalog;


use App\Http\Admin\Requests\AdminRequest;

class CatalogImportRequest extends AdminRequest
{

    public function rules(): array
    {
        return [
            'file_id' => 'required|string',
        ];
    }

}

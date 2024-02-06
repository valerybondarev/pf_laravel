<?php


namespace App\Http\Admin\Requests\Tools;


use App\Http\Admin\Requests\AdminRequest;

class UploadFileRequest extends AdminRequest
{

    public function rules(): array
    {
        return [
            'file' => 'required|file',
        ];
    }

}

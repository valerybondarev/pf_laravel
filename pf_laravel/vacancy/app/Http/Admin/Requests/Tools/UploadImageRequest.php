<?php


namespace App\Http\Admin\Requests\Tools;


use App\Http\Admin\Requests\AdminRequest;

class UploadImageRequest extends AdminRequest
{

    public function rules(): array
    {
        return [
            'image' => 'required|image',
        ];
    }

}

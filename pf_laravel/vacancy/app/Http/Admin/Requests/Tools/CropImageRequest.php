<?php


namespace App\Http\Admin\Requests\Tools;


use App\Http\Admin\Requests\AdminRequest;

class CropImageRequest extends AdminRequest
{

    public function rules(): array
    {
        return [
            'image' => 'required|image',
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
            'x' => 'required|integer|min:0',
            'y' => 'required|integer|min:0',
        ];
    }

}

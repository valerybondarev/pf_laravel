<?php


namespace App\Http\Admin\Requests;


use Illuminate\Foundation\Http\FormRequest;

abstract class AdminRequest extends FormRequest
{
    public function attributes()
    {
        return collect($this->rules())
            ->mapWithKeys(function ($value, $key) {
                return [$key => __("admin.columns.$key")];
            })
            ->all();
    }
}

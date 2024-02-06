<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SessionUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'step' => 'required|integer|min:0|max:16',
            'session' => 'required',
            'device_id' => 'string|nullable',
            'nickname' => 'string|nullable',
            'file' => 'required|file',
            'ModelPhone' => 'required',
        ];
    }
}

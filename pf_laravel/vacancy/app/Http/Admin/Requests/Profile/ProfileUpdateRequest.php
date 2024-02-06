<?php

namespace App\Http\Admin\Requests\Profile;

use App\Http\Admin\Requests\AdminRequest;

class ProfileUpdateRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'firstName'  => 'nullable|string|max:255',
            'lastName'   => 'nullable|string|max:255',
            'avatarId'   => 'nullable|exists:images,id',
            'languageId' => 'nullable|exists:languages,id',
        ];
    }

}

<?php


namespace App\Http\Admin\Requests\Auth;

use App\Http\Admin\Requests\AdminRequest;

class LoginRequest extends AdminRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean'
        ];
    }

    public function attributes()
    {
        return [
            'username' => '',
            'password' => '',
        ];
    }

    public function credentials(): array
    {
        return [
            'email'    => $this->get('username'),
            'phone'    => $this->get('username'),
            'username' => $this->get('username'),
            'password' => $this->get('password'),
            'remember' => (bool)$this->get('remember'),
        ];
    }
}

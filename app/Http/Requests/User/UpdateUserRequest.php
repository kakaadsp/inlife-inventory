<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isAdmin(); }

    public function rules(): array
    {
        return [
            'role_id'   => ['required', 'exists:roles,id'],
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'password'  => ['nullable', 'confirmed', Password::min(8)],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}

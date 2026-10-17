<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'role'     => ['required', 'in:student,admin'],
            'nim'      => ['required_if:role,student', 'nullable', 'string', 'max:30', 'unique:users,nim'],
            'username' => ['required_if:role,admin', 'nullable', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}

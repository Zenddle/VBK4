<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('user')->id;
        return [
            'name'     => ['required', 'string', 'max:255'],
            'role'     => ['required', 'in:student,admin'],
            'nim'      => ['required_if:role,student', 'nullable', 'string', 'max:30', Rule::unique('users', 'nim')->ignore($id)],
            'username' => ['required_if:role,admin', 'nullable', 'string', 'max:50', Rule::unique('users', 'username')->ignore($id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }
}

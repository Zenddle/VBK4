<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /** Mahasiswa login dengan NIM, admin dengan username (sama seperti login web). */
    public function login(Request $request): JsonResponse
    {
        // NIM yang dikirim sebagai angka (tanpa tanda kutip) tetap diterima
        if (is_int($request->input('identifier'))) {
            $request->merge(['identifier' => (string) $request->input('identifier')]);
        }

        $validator = Validator::make($request->all(), [
            'type'       => ['sometimes', 'in:student,admin'],
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('VALIDATION_ERROR', 'Data yang dikirim tidak valid.', 400, $validator->errors()->toArray());
        }

        $type  = $request->input('type', 'student');
        $field = $type === 'admin' ? 'username' : 'nim';

        $user = User::where($field, $request->input('identifier'))->where('role', $type)->first();

        // pesan sama untuk "akun tidak ada" dan "password salah"
        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return ApiResponse::error('INVALID_CREDENTIALS', 'NIM/username atau password salah.', 401);
        }

        return ApiResponse::success([
            'token'      => $user->createToken('api')->plainTextToken,
            'token_type' => 'Bearer',
            'user'       => [
                'id'       => $user->id,
                'name'     => $user->name,
                'nim'      => $user->nim,
                'username' => $user->username,
                'role'     => $user->role,
            ],
        ]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        // student logs in with NIM, admin with username
        $field = $request->type === 'admin' ? 'username' : 'nim';

        $ok = Auth::attempt([
            $field     => $request->identifier,
            'password' => $request->password,
            'role'     => $request->type,
        ], $request->boolean('remember'));

        if (! $ok) {
            return back()
                ->withInput($request->only('identifier', 'type'))
                ->withErrors(['identifier' => 'Wrong credentials.']);
        }

        $request->session()->regenerate();
        return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

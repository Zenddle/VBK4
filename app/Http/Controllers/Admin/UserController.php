<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$s}%")
                ->orWhere('nim', 'like', "%{$s}%")
                ->orWhere('username', 'like', "%{$s}%")))
            ->orderBy('role')->orderBy('nim')
            ->paginate(10)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());
        return back()->with('success', 'User created.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (empty($data['password'])) unset($data['password']);
        $user->update($data);
        return back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete(); // soft delete keeps loan history
        return back()->with('success', 'User deleted.');
    }
}

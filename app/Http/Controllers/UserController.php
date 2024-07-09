<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return Inertia::render('admin/user/Index', [
            'title' => 'Users',
            'users' => UserResource::collection($users)
        ]);
    }

    public function createPage()
    {
        return Inertia::render('admin/user/Create', [
            'title' => 'Create User',
            'back' => '/admin/users'
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:5',
            'phone' => 'required|string|min:10',
            'address' => 'nullable|string',
            'role' => 'required|string|in:user,admin,worker',
        ]);

        User::create([
            'full_name' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ]);

        return redirect()->intended('/admin/users')->with('toast', "User Berhasil Ditambah");
    }

    public function editPage($id)
    {
        $user = User::find($id);
        return Inertia::render('admin/user/Edit', [
            'title' => 'Edit User',
            'back' => '/admin/users',
            'user' => new UserResource($user)
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $user->update([
            'full_name' => $request->fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => $request->role,
        ]);
        return redirect()->intended('/admin/users')->with('toast', "User Berhasil Diubah");
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->intended('/admin/users')->with('toast', "User Berhasil Dihapus");
    }
}

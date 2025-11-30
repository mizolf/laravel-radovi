<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Update the role of a user
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:admin,nastavnik,student'],
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User role updated successfully!');
    }
}

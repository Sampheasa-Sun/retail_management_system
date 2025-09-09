<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
        ]);

        return redirect()->route('users.index')->with('success', 'Employee account created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Only update the password if a new one was entered
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Employee account updated successfully.');
    }

    /**
     * Handle bulk actions on users.
     */
    public function handleBulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:activate,pause,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->input('user_ids');
        $action = $request->input('action');
        
        $userIds = array_diff($userIds, [auth()->id()]);

        if (empty($userIds)) {
            return back()->with('error', 'No users were selected or you cannot perform actions on your own account.');
        }

        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = 'Selected employee(s) have been activated.';
                break;
            case 'pause':
                User::whereIn('id', $userIds)->update(['is_active' => false]);
                $message = 'Selected employee(s) have been paused.';
                break;
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Selected employee(s) have been deleted.';
                break;
        }

        return redirect()->route('users.index')->with('success', $message);
    }
}

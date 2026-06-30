<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-list', only: ['index', 'show']),
            new Middleware('permission:user-create', only: ['create', 'store']),
            new Middleware('permission:user-edit', only: ['edit', 'update']),
            new Middleware('permission:user-delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        $users = User::query()
            ->with(['roles', 'creator'])
            ->latest('id')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'developer_type' => ['required', 'string', 'in:zone,enterprise'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? null,
            'company_logo' => $validated['company_logo'] ?? null,
            'developer_type' => $validated['developer_type'],
            'created_by' => Auth::id(),
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'creator']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = Role::query()->orderBy('name')->get();
        $selectedRoles = $user->roles()->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'developer_type' => ['required', 'string', 'in:zone,enterprise'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->name = $validated['name'];
        $user->company_name = $validated['company_name'] ?? null;
        $user->developer_type = $validated['developer_type'];
        $user->email = $validated['email'];

        if ($request->hasFile('company_logo')) {
            if ($user->company_logo) {
                Storage::disk('public')->delete($user->company_logo);
            }

            $user->company_logo = $request->file('company_logo')->store('company-logos', 'public');
        }

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $selectedRoles = collect($validated['roles']);
        $authUser = Auth::user();

        if ($user->hasRole('admin') && ! $selectedRoles->contains('admin') && User::role('admin')->count() <= 1) {
            return redirect()->back()->withInput()->with('error', 'At least one admin user must remain assigned.');
        }

        if ($authUser && $authUser->id === $user->id && $authUser->hasRole('admin') && ! $selectedRoles->contains('admin')) {
            return redirect()->back()->withInput()->with('error', 'You cannot remove the admin role from your own account.');
        }

        $user->save();
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $authUser = Auth::user();

        if ($authUser && $authUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account from this page.');
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->route('users.index')->with('error', 'The last admin user cannot be archived.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User archived successfully.');
    }
}

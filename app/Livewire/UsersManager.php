<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class UsersManager extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $isEditing = false;
    public $editingUserId = null;

    public $name = '';
    public $company_name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public $company_logo;
    public $developer_type = 1;

    public $confirmDeleteId = null;

    protected function rules(): array
    {
        $unique = 'unique:users,email';
        $id = $this->editingUserId;
        if ($id) {
            $unique .= ',' . $id;
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'developer_type' => ['required', 'integer', 'in:1,2'],
            'email' => ['required', 'string', 'email', 'max:255', $unique],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['string', 'exists:roles,name'],
        ];

        if ($this->isEditing) {
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
        } else {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    public function render()
    {
        return view('livewire.users-manager', [
            'users' => User::with(['roles', 'creator'])->latest('id')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function openCreate()
    {
        $this->authorize('user-create');
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = false;
        $this->editingUserId = null;
    }

    public function openEdit($id)
    {
        $this->authorize('user-edit');
        $user = User::findOrFail($id);
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = true;
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->company_name = $user->company_name ?? '';
        $this->email = $user->email;
        $this->developer_type = $user->developer_type ?? 1;
        $this->selectedRoles = $user->roles()->pluck('name')->toArray();
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('user-edit');
        } else {
            $this->authorize('user-create');
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'company_name' => $this->company_name ?: null,
            'developer_type' => $this->developer_type,
            'email' => $this->email,
        ];

        if ($this->company_logo) {
            $data['company_logo'] = $this->company_logo->store('company-logos', 'public');
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->editingUserId);

            if (! empty($this->password)) {
                $user->password = $this->password;
            }

            if ($this->company_logo && $user->company_logo) {
                Storage::disk('public')->delete($user->company_logo);
            }

            $selectedRoles = collect($this->selectedRoles);
            $authUser = Auth::user();

            if ($user->hasRole('admin') && !$selectedRoles->contains('admin') && User::role('admin')->count() <= 1) {
                $this->addError('selectedRoles', 'At least one admin user must remain assigned.');
                return;
            }

            if ($authUser && $authUser->id === $user->id && $authUser->hasRole('admin') && !$selectedRoles->contains('admin')) {
                $this->addError('selectedRoles', 'You cannot remove the admin role from your own account.');
                return;
            }

            $user->update($data);
            $user->syncRoles($this->selectedRoles);
            session()->flash('success', 'User updated successfully.');
        } else {
            $data['created_by'] = Auth::id();
            $data['password'] = $this->password;
            $user = User::create($data);
            $user->syncRoles($this->selectedRoles);
            session()->flash('success', 'User created successfully.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->authorize('user-delete');
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function delete()
    {
        $this->authorize('user-delete');
        $user = User::findOrFail($this->confirmDeleteId);

        if (Auth::id() === $user->id) {
            session()->flash('error', 'You cannot archive your own account.');
            $this->confirmDeleteId = null;
            return;
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            session()->flash('error', 'The last admin user cannot be archived.');
            $this->confirmDeleteId = null;
            return;
        }

        $user->delete();
        $this->confirmDeleteId = null;
        session()->flash('success', 'User archived successfully.');
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'company_name', 'developer_type', 'email', 'password', 'password_confirmation',
            'selectedRoles', 'company_logo', 'editingUserId',
        ]);
    }
}

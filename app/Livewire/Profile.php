<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $company_name;
    public $email;
    public $company_logo;
    public $password;
    public $password_confirmation;

    public $confirmDeletePassword = '';
    public $showDeleteConfirm = false;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->company_name = $user->company_name ?? '';
        $this->email = $user->email;
    }

    public function render()
    {
        return view('livewire.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function save()
    {
        $user = auth()->user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $data = [
            'name' => $this->name,
            'company_name' => $this->company_name ?: null,
            'email' => $this->email,
        ];

        if ($this->company_logo) {
            if ($user->company_logo) {
                Storage::disk('public')->delete($user->company_logo);
            }
            $data['company_logo'] = $this->company_logo->store('company-logos', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        $user->update($data);

        session()->flash('success', 'Profile updated successfully.');
    }

    public function confirmDelete()
    {
        $this->validate([
            'confirmDeletePassword' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            $this->addError('confirmDeletePassword', 'You are the last admin and cannot delete this account.');
            return;
        }

        Auth::logout();
        $user->delete();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }
}

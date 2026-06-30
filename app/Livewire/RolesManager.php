<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesManager extends Component
{
    public $showForm = false;
    public $isEditing = false;
    public $editingRoleId = null;
    public $name = '';
    public $selectedPermissions = [];
    public $confirmDeleteId = null;

    protected function rules(): array
    {
        $unique = 'unique:roles,name';
        $id = $this->editingRoleId;
        if ($id) {
            $unique .= ',' . $id;
        }
        return [
            'name' => ['required', 'string', 'max:50', $unique],
            'selectedPermissions' => ['nullable', 'array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function render()
    {
        return view('livewire.roles-manager', [
            'roles' => Role::withCount('permissions')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = false;
        $this->editingRoleId = null;
    }

    public function openEdit($id)
    {
        $role = Role::findOrFail($id);
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = true;
        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions()->pluck('name')->toArray();
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('role-edit');
        } else {
            $this->authorize('role-create');
        }

        $this->validate();

        if ($this->isEditing) {
            $role = Role::findOrFail($this->editingRoleId);
            if ($role->name === 'admin' && $this->name !== 'admin') {
                $this->addError('name', 'Default admin role name cannot be changed.');
                return;
            }
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions ?? []);
            session()->flash('success', 'Role updated successfully.');
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions ?? []);
            session()->flash('success', 'Role created successfully.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->authorize('role-delete');
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    public function delete()
    {
        $this->authorize('role-delete');
        $role = Role::findOrFail($this->confirmDeleteId);
        if ($role->name === 'admin') {
            session()->flash('error', 'The admin role cannot be deleted.');
            $this->confirmDeleteId = null;
            return;
        }
        $role->delete();
        $this->confirmDeleteId = null;
        session()->flash('success', 'Role deleted successfully.');
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'selectedPermissions', 'editingRoleId']);
    }
}

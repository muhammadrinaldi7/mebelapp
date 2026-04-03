<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RoleManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $roleId;
    public $name = '';
    public $selectedPermissions = [];

    public $showPermissionModal = false;
    public $permissionName = '';

    public $confirmingDelete = false;
    public $deleteId;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function create()
    {
        $this->reset(['roleId', 'name', 'selectedPermissions', 'editMode']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
            $this->dispatch('notify', type: 'success', message: 'Role berhasil diperbarui.');
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
            $this->dispatch('notify', type: 'success', message: 'Role berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['roleId', 'name', 'selectedPermissions', 'editMode']);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        Role::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Role berhasil dihapus.');
    }

    public function createPermission()
    {
        $this->validate(['permissionName' => 'required|string|max:255|unique:permissions,name']);
        Permission::create(['name' => $this->permissionName]);
        $this->permissionName = '';
        $this->showPermissionModal = false;
        $this->dispatch('notify', type: 'success', message: 'Permission berhasil ditambahkan.');
    }

    public function deletePermission($id)
    {
        Permission::findOrFail($id)->delete();
        $this->dispatch('notify', type: 'success', message: 'Permission berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.role-management', [
            'roles' => Role::with('permissions')->paginate(10),
            'permissions' => Permission::all(),
        ]);
    }
}


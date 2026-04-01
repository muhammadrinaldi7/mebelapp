<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';

    public $confirmingDelete = false;
    public $deleteId;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->editMode ? ',' . $this->userId : ''),
            'password' => $this->editMode ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function create()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'editMode']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->roles->first()?->name ?? '';
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }
            $user->syncRoles([$this->role]);
            session()->flash('message', 'User berhasil diperbarui.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole($this->role);
            session()->flash('message', 'User berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'editMode']);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        User::findOrFail($this->deleteId)->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }
}

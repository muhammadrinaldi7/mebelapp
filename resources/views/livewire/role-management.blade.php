<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Role & Permission</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola role dan hak akses.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <button wire:click="$set('showPermissionModal', true)" class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                + Permission
            </button>
            <button wire:click="create" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Tambah Role
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-4"><p class="text-sm text-green-700">{{ session('message') }}</p></div>
    @endif

    {{-- Roles Table --}}
    <div class="mt-6 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Role</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Permissions</th>
                    <th class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($roles as $role)
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-sm font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ ucfirst($role->name) }}</span>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500">
                        <div class="flex flex-wrap gap-1">
                            @forelse($role->permissions as $perm)
                                <span class="inline-flex items-center rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">{{ $perm->name }}</span>
                            @empty
                                <span class="text-gray-400 italic text-xs">Belum ada permission</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium space-x-2">
                        <button wire:click="edit({{ $role->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <button wire:click="confirmDelete({{ $role->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="py-4 text-center text-sm text-gray-500">Belum ada data role.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $roles->links() }}</div>

    {{-- All Permissions Table --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-900">Daftar Permission</h2>
        <div class="mt-3 flex flex-wrap gap-2">
            @forelse($permissions as $perm)
                <div class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700">
                    {{ $perm->name }}
                    <button wire:click="deletePermission({{ $perm->id }})" wire:confirm="Hapus permission '{{ $perm->name }}'?" class="text-red-400 hover:text-red-600 ml-1">✕</button>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic">Belum ada permission.</p>
            @endforelse
        </div>
    </div>

    {{-- Create/Edit Role Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        <div class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
                <h3 class="text-lg font-semibold text-gray-900">{{ $editMode ? 'Edit Role' : 'Tambah Role' }}</h3>
                <form wire:submit="save" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Role</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded-md p-3">
                            @foreach($permissions as $perm)
                                <label class="flex items-center gap-2 text-sm">
                                    <input wire:model="selectedPermissions" type="checkbox" value="{{ $perm->name }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    {{ $perm->name }}
                                </label>
                            @endforeach
                            @if($permissions->isEmpty())
                                <p class="col-span-2 text-sm text-gray-400 italic">Belum ada permission. Buat terlebih dahulu.</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Simpan</button>
                    </div>
                </form>
        </div>
    </div>
    @endif

    {{-- Create Permission Modal --}}
    @if($showPermissionModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="$set('showPermissionModal', false)"></div>
        <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Permission</h3>
                <form wire:submit="createPermission" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Permission</label>
                        <input wire:model="permissionName" type="text" placeholder="contoh: manage-products" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('permissionName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showPermissionModal', false)" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Simpan</button>
                    </div>
                </form>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation --}}
    @if($confirmingDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus role ini?</p>
                <div class="mt-4 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDelete', false)" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                    <button wire:click="delete" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Hapus</button>
                </div>
        </div>
    </div>
    @endif
</div>

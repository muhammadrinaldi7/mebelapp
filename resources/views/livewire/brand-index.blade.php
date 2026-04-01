<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Merek</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola data merek mebel.</p>
        </div>
        <button wire:click="create" class="mt-4 sm:mt-0 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Tambah Merek
        </button>
    </div>

    @if(session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-4"><p class="text-sm text-green-700">{{ session('message') }}</p></div>
    @endif

    <div class="mt-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari merek..." class="block w-full sm:w-64 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
    </div>

    <div class="mt-6 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Deskripsi</th>
                    <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Jumlah Produk</th>
                    <th class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($brands as $brand)
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $brand->name }}</td>
                    <td class="px-3 py-4 text-sm text-gray-500">{{ Str::limit($brand->description, 50) ?? '-' }}</td>
                    <td class="px-3 py-4 text-sm text-center text-gray-500">{{ $brand->products_count }}</td>
                    <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium space-x-2">
                        <button wire:click="edit({{ $brand->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <button wire:click="confirmDelete({{ $brand->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-center text-sm text-gray-500">Belum ada data merek.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $brands->links() }}</div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        <div class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
            <h3 class="text-lg font-semibold text-gray-900">{{ $editMode ? 'Edit Merek' : 'Tambah Merek' }}</h3>
            <form wire:submit="save" class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Merek</label>
                    <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
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
            <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus merek ini?</p>
            <div class="mt-4 flex justify-end gap-3">
                <button wire:click="$set('confirmingDelete', false)" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                <button wire:click="delete" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>

<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Metode Pembayaran</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola metode pembayaran yang tersedia untuk transaksi penjualan.</p>
        </div>
        <button wire:click="create"
            class="mt-4 sm:mt-0 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Tambah Metode
        </button>
    </div>

    <div class="mt-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari metode pembayaran..."
            class="block w-full sm:w-64 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
    </div>

    <div class="mt-6 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Nama Metode</th>
                    <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Status</th>
                    <th class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($methods as $method)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                {{ $method->name }}
                            </div>
                        </td>
                        <td class="px-3 py-4 text-center">
                            <button wire:click="toggleActive({{ $method->id }})"
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold transition-colors cursor-pointer
                                {{ $method->is_active
                                    ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 hover:bg-emerald-100'
                                    : 'bg-gray-100 text-gray-500 ring-1 ring-inset ring-gray-300 hover:bg-gray-200' }}">
                                <span
                                    class="mr-1.5 h-1.5 w-1.5 rounded-full {{ $method->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                {{ $method->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium space-x-2">
                            <button wire:click="edit({{ $method->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="confirmDelete({{ $method->id }})"
                                class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-sm text-gray-500">Belum ada metode pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $methods->links() }}</div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                wire:click="$set('showModal', false)"></div>
            <div class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $editMode ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}</h3>
                <form wire:submit="save" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Metode</label>
                        <input wire:model="name" type="text" placeholder="Contoh: Transfer BCA"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="is_active" type="checkbox" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                            </div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">Aktif</span>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation --}}
    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
            <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus metode pembayaran ini? Metode
                    yang sudah digunakan dalam transaksi tidak bisa dihapus.</p>
                <div class="mt-4 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDelete', false)"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                    <button wire:click="delete"
                        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>

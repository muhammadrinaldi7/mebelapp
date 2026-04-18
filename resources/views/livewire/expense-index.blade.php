<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pengeluaran Operasional</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan catat biaya operasional gudang serta bensin.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openModal"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 focus:outline-hidden transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Catat Pengeluaran
            </button>
        </div>
    </div>

    {{-- Stats Cards & Filters --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Total Stats --}}
        <div
            class="lg:col-span-1 rounded-2xl bg-white p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('report.export', ['type' => 'pdf', 'tab' => 'expenses', 'from' => $dateFrom, 'to' => $dateTo]) }}"
                        class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Export PDF">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </a>
                    <a href="{{ route('report.export', ['type' => 'excel', 'tab' => 'expenses', 'from' => $dateFrom, 'to' => $dateTo]) }}"
                        class="p-2 text-gray-400 hover:text-emerald-500 transition-colors" title="Export Excel">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5V4.5m0 15H2.25m19.5 0a1.125 1.125 0 001.125-1.125M21.75 19.5V4.5m0 15H22.5m-22.5-15h22.5m-22.5 0a1.125 1.125 0 011.125-1.125m0 0h17.25a1.125 1.125 0 011.125 1.125M3 3.375l1.921 14.566A3 3 0 007.89 21h8.22a3 3 0 002.97-3.059L21 3.375m-18 0h18" />
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rp
                    {{ number_format($totalAmount, 0, ',', '.') }}</h2>
                <p class="text-xs text-gray-400 mt-2 italic">* Periode Terpilih</p>
            </div>
        </div>

        {{-- Filters --}}
        <div
            class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-xs border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-1">Cari
                    Kategori/Catatan</label>
                <div class="relative">
                    <input type="text" wire:model.live="search"
                        class="w-full h-11 pl-10 px-4 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        placeholder="Misal: Bensin...">
                    <svg class="absolute left-3.5 top-3.5 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-1">Dari Tanggal</label>
                <input type="date" wire:model.live="dateFrom"
                    class="w-full h-11 bg-gray-50 px-4 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="dateTo"
                    class="w-full h-11 bg-gray-50 px-4 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Kategori
                        </th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">
                            Nominal</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Catatan</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">
                            Struk</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($expenses as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $item->expense_date->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold text-gray-900">Rp
                                    {{ number_format($item->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-500 line-clamp-1 max-w-[200px]" title="{{ $item->notes }}">
                                    {{ $item->notes ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($item->receipt_image)
                                    <a href="{{ Storage::url($item->receipt_image) }}" target="_blank"
                                        class="inline-flex items-center p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-[10px] text-gray-300 font-medium italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button
                                        onclick="confirm('Yakin ingin menghapus data ini?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $item->id }})"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="text-sm">Belum ada data pengeluaran untuk periode ini.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($expenses->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Form --}}
    @if ($showModal)
        <div wire:key="expense-modal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/40 backdrop-blur-sm" wire:click="closeModal">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div>
                        <div class="px-6 pt-6 pb-4 bg-white">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">{{ $isEditing ? 'Ubah' : 'Tambah' }}
                                    Pengeluaran</h3>
                                <button type="button" wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Tanggal</label>
                                        <input type="date" wire:model="expense_date" required
                                            class="w-full h-11 px-4 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @error('expense_date')
                                            <span class="text-[10px] text-red-500 px-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label
                                            class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Nominal
                                            (Rp)</label>
                                        <input type="number" wire:model="amount" required step="0.01"
                                            class="w-full h-11 bg-gray-50 px-4 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="0">
                                        @error('amount')
                                            <span class="text-[10px] text-red-500 px-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Kategori
                                        / Judul</label>
                                    <input type="text" wire:model="category" required
                                        class="w-full h-11 px-4 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Misal: Bensin Truk, Paket Makan Siang...">
                                    @error('category')
                                        <span class="text-[10px] text-red-500 px-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Catatan</label>
                                    <textarea wire:model="notes" rows="2"
                                        class="w-full bg-gray-50 px-4 py-2 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                        placeholder="Detail pengeluaran..."></textarea>
                                    @error('notes')
                                        <span class="text-[10px] text-red-500 px-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Bukti
                                        Struk (Foto)</label>
                                    <div class="mt-1 flex items-center gap-4">
                                        <div
                                            class="relative w-20 h-20 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                            @if ($receipt_image)
                                                <img src="{{ $receipt_image->temporaryUrl() }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" wire:model="receipt_image" accept="image/*"
                                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all" />
                                            <p class="mt-1.5 text-[10px] text-gray-400">JPG, PNG atau WEBP Max. 2MB</p>
                                        </div>
                                    </div>
                                    <div wire:loading wire:target="receipt_image"
                                        class="text-[10px] text-indigo-500 font-medium animate-pulse">Sedang memproses
                                        gambar...</div>
                                    @error('receipt_image')
                                        <span class="text-[10px] text-red-500 px-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 transition-all">
                                Batal
                            </button>
                            <button type="button" wire:click="save" wire:loading.attr="disabled"
                                class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 disabled:opacity-50 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Data Stock Opname
            </h2>
            <p class="text-sm text-gray-600">Kelola riwayat penyesuaian stok fisik dan sistem</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('stock-opname.export', ['type' => 'excel', 'search' => $search]) }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('stock-opname.export', ['type' => 'pdf', 'search' => $search]) }}" target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Export PDF
            </a>
            <a href="{{ route('stock-opname.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah Opname
            </a>
        </div>
    </div>

    <!-- Stats & Filters -->
    <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="w-full md:w-1/3">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="block w-full rounded-xl border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="Cari kode referensi atau user...">
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Kode Referensi</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Tanggal</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Admin</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Keterangan</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($opnames as $opname)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <button wire:click="showDetail({{ $opname->id }})"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $opname->reference_code }}
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    {{ $opname->opname_date->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $opname->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $opname->notes ?: '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($opname->status == 'completed')
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                        Selesai
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('stock-opname.export.detail', $opname->id) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="mb-4 h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900">Belum ada data</h3>
                                    <p class="mt-1">Belum ada riwayat stock opname yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($opnames->hasPages())
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                {{ $opnames->links() }}
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    @if ($isDetailModalOpen && $selectedOpname)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeDetailModal"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block bg-black/50 backdrop-blur-sm transition-opacity overflow-hidden rounded-2xl text-left align-bottom shadow-xl  sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-xl font-bold leading-6 text-gray-900 mb-4" id="modal-title">
                                    Detail Stock Opname <span
                                        class="text-blue-600">#{{ $selectedOpname->reference_code }}</span>
                                </h3>

                                <div
                                    class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tanggal
                                        </p>
                                        <p class="font-medium text-gray-900">
                                            {{ $selectedOpname->opname_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Admin
                                        </p>
                                        <p class="font-medium text-gray-900">{{ $selectedOpname->user->name }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                                            Keterangan Umum</p>
                                        <p class="font-medium text-gray-900">{{ $selectedOpname->notes ?: '-' }}</p>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Produk</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                                                    Stok Sistem</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                                                    Stok Fisik</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                                                    Selisih</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                                    Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach ($selectedOpname->details as $detail)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $detail->product->name }}</div>
                                                        <div class="text-xs text-gray-500">SKU:
                                                            {{ $detail->product->sku }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span
                                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                                            {{ $detail->system_stock }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center font-semibold text-gray-900">
                                                        {{ $detail->physical_stock }}
                                                    </td>
                                                    <td class="px-4 py-3 text-center font-bold">
                                                        @if ($detail->difference > 0)
                                                            <span
                                                                class="text-green-600">+{{ $detail->difference }}</span>
                                                        @elseif($detail->difference < 0)
                                                            <span
                                                                class="text-red-600">{{ $detail->difference }}</span>
                                                        @else
                                                            <span class="text-gray-500">0</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate"
                                                        title="{{ $detail->notes }}">
                                                        {{ $detail->notes ?: '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="button" wire:click="closeDetailModal"
                            class="mt-3 inline-flex w-full justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

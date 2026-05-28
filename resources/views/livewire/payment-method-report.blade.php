<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Metode Pembayaran</h1>
            <p class="mt-1 text-sm text-gray-500">Laporan mutasi berdasarkan metode pembayaran yang tersedia.</p>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row items-center gap-4 bg-white p-4 shadow ring-1 ring-black/5 sm:rounded-lg">
        <div class="w-full sm:w-auto">
            <label for="startDate" class="block text-xs font-medium text-gray-700">Dari Tanggal</label>
            <input type="date" id="startDate" wire:model.live="startDate"
                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div class="hidden sm:block mt-5 text-gray-400">
            &mdash;
        </div>
        <div class="w-full sm:w-auto">
            <label for="endDate" class="block text-xs font-medium text-gray-700">Sampai Tanggal</label>
            <input type="date" id="endDate" wire:model.live="endDate"
                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div wire:loading class="mt-5 text-sm text-indigo-600 font-medium">
            Memuat data...
        </div>
    </div>

    <div class="mt-6 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Nama Metode</th>
                    <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Jumlah Transaksi</th>
                    <th class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900">Total Mutasi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                {{-- Pastikan variabel $reportPayments sesuai dengan yang di-passing dari class Livewire --}}
                @forelse($reportPayments as $method)
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
                        <td class="px-3 py-4 text-center text-sm text-gray-700">
                            <button
                                wire:click="viewTransaction({{ $method->id }}, '{{ $startDate }}', '{{ $endDate }}')"
                                class="text-indigo-600 hover:text-indigo-900">
                                {{ number_format($method->jumlah_transaksi, 0, ',', '.') }}
                            </button>
                        </td>
                        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium text-gray-900">
                            Rp {{ number_format($method->total_mutasi_masuk, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-sm text-gray-500">
                            Tidak ada metode pembayaran yang aktif atau ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showTransactions)
        <div class="mt-6 overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
            <div class="flex justify-end">
                <button wire:click="$set('showTransactions', false)" class="text-indigo-600 hover:text-indigo-900">
                    Tutup
                </button>
            </div>
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">No Transaksi</th>
                        <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Tanggal</th>
                        <th class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                {{ $transaction->transaction->customer_name }}
                            </td>
                            <td class="px-3 py-4 text-center text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($transaction->transaction->transaction_date)->format('d-m-Y') }}
                            </td>
                            <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium text-gray-900">
                                Rp {{ number_format($transaction->transaction->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-sm text-gray-500">
                                Tidak ada transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>

<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Keep Stock</h1>
            <p class="mt-1 text-sm text-gray-500">Data barang yang di keep.</p>
        </div>
    </div>
    <div class="mt-4 overflow-auto shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Kode</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">User</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Catatan</th>
                    @can('hapus-barang-masuk')
                        @unless (Auth::user()->hasRole('admin'))
                            <th class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900">Aksi</th>
                        @endunless
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($transactions as $trx)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">

                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trx->user->name ?? '-' }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">

                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">

                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center">

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-sm text-gray-500">Belum ada transaksi barang
                            masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

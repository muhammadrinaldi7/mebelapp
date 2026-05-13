<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
            <p class="mt-1 text-sm text-gray-500">Daftar riwayat aktivitas pengguna dalam sistem.</p>
        </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari aktivitas atau user..."
            class="block w-full sm:w-64 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm">

        <select wire:model.live="type"
            class="block w-full sm:w-48 rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 sm:text-sm">
            <option value="">Semua Tipe</option>
            @foreach ($types as $t)
                <option value="{{ $t }}">{{ str_replace('App\\Models\\', '', $t) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-6 overflow-x-auto shadow ring-1 ring-black/5 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Waktu</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">User</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Aksi</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Model</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Keterangan</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Perubahan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($logs as $log)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                            {{ $log->causer->name ?? 'System' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @php
                                $badgeClass = match ($log->description) {
                                    'created' => 'bg-green-50 text-green-700 ring-green-600/20',
                                    'updated' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'deleted' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center rounded-md {{ $badgeClass }} px-2 py-1 text-xs font-medium ring-1 ring-inset uppercase">
                                {{ $log->description }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            {{ str_replace('App\\Models\\', '', $log->subject_type) }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            ID: {{ $log->subject_id }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">
                            @if (isset($log->properties['attributes']) || isset($log->properties['old']))
                                <button type="button" x-data=""
                                    @click="$dispatch('open-log-modal', { logId: {{ $log->id }} })"
                                    class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">
                                    Lihat Detail
                                </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-sm text-gray-500">Belum ada riwayat aktivitas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>

    {{-- Detail Modal --}}
    <div x-data="{ 
            open: false, 
            logData: null,
            async loadData(id) {
                this.logData = null;
                this.open = true;
                this.logData = await $wire.getLogDetails(id);
            }
        }" 
        x-on:open-log-modal.window="loadData($event.detail.logId)"
        class="relative z-50" x-show="open" x-cloak>

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Detail
                                    Perubahan</h3>
                                <div class="mt-4 overflow-x-auto">
                                    <template x-if="logData">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Field</th>
                                                    <th
                                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Lama</th>
                                                    <th
                                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Baru</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <template
                                                    x-for="(value, key) in (logData.attributes || logData.old || {})">
                                                    <tr>
                                                        <td class="px-3 py-2 text-sm font-medium text-gray-900"
                                                            x-text="key"></td>
                                                        <td class="px-3 py-2 text-sm text-gray-500 italic"
                                                            x-text="logData.old ? logData.old[key] : '-'"></td>
                                                        <td class="px-3 py-2 text-sm text-gray-700 font-semibold"
                                                            x-text="logData.attributes ? logData.attributes[key] : '-'">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </template>
                                    <div x-show="!logData" class="py-10 text-center text-gray-500">
                                        Memuat...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                            @click="open = false">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

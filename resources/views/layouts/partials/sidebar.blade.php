<div class="flex grow flex-col ios-sidebar h-full">
    {{-- Logo area --}}
    <div class="flex items-center gap-3 px-5 pt-6 pb-4">
        <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
            <span class="text-xl">
                <img src="{{ asset('storage/img/mebellogo.png') }}" alt="Logo" class="w-10 h-10 bg-white rounded-3xl">
            </span>
        </div>
        <div>
            <span class="text-[17px] font-semibold text-white tracking-tight">{{ config('app.name') }}</span>
            <span class="block text-[11px] text-indigo-300/80 font-medium tracking-wide uppercase">Stock
                Management</span>
        </div>
    </div>

    <nav class="flex flex-1 flex-col px-3 mt-1 overflow-y-auto">
        <ul role="list" class="flex flex-1 flex-col gap-y-5">
            {{-- Main Navigation --}}
            <li>
                <ul role="list" class="space-y-0.5">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="ios-nav-item {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}"
                            class="ios-nav-item {{ request()->routeIs('products.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('brands.index') }}"
                            class="ios-nav-item {{ request()->routeIs('brands.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            Merek
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="ios-nav-item {{ request()->routeIs('categories.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z" />
                            </svg>
                            Kategori
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Transaksi Section --}}
            <li>
                <div class="text-[11px] font-semibold text-indigo-300/70 uppercase tracking-widest px-3 mb-1.5">
                    Transaksi</div>
                <ul role="list" class="space-y-0.5">
                    <li>
                        <a href="{{ route('transactions.in') }}"
                            class="ios-nav-item {{ request()->routeIs('transactions.in') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Barang Masuk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.out') }}"
                            class="ios-nav-item {{ request()->routeIs('transactions.out') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3" />
                            </svg>
                            Barang Keluar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sales.index') }}"
                            class="ios-nav-item {{ request()->routeIs('sales.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            Penjualan
                        </a>
                    </li>
                    @role('admin')
                    <li>
                        <a href="{{ route('expenses.index') }}"
                            class="ios-nav-item {{ request()->routeIs('expenses.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                            <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            Pengeluaran
                        </a>
                    </li>
                    @endrole
                </ul>
            </li>

            {{-- Laporan Section --}}
            @can('lihat-laporan')
                <li>
                    <div class="text-[11px] font-semibold text-indigo-300/70 uppercase tracking-widest px-3 mb-1.5">Laporan
                    </div>
                    <ul role="list" class="space-y-0.5">
                        <li>
                            <a href="{{ route('reports.index') }}"
                                class="ios-nav-item {{ request()->routeIs('reports.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                                Laporan & Export
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            {{-- Admin Section --}}
            @role('admin')
                <li>
                    <div class="text-[11px] font-semibold text-indigo-300/70 uppercase tracking-widest px-3 mb-1.5">
                        Administrasi</div>
                    <ul role="list" class="space-y-0.5">
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="ios-nav-item {{ request()->routeIs('users.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Kelola User
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('roles.index') }}"
                                class="ios-nav-item {{ request()->routeIs('roles.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-100/90 hover:bg-white/10 hover:text-white' }} group flex gap-x-3 rounded-xl px-3 py-2.5 text-[14px] font-medium">
                                <svg class="h-5 w-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                                Role & Permission
                            </a>
                        </li>
                    </ul>
                </li>
            @endrole

            {{-- User profile at bottom --}}
            <li class="mt-auto pb-4">
                <div class="rounded-2xl bg-white/10 p-3">
                    <div class="flex items-center gap-x-3">
                        <div
                            class="h-9 w-9 rounded-full bg-linear-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="block text-[13px] font-semibold text-white truncate">{{ auth()->user()->name ?? 'User' }}</span>
                            <span
                                class="block text-[11px] text-indigo-300/80 truncate capitalize">{{ auth()->user()->roles->first()->name ?? '' }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="p-1.5 rounded-lg text-indigo-300/80 hover:text-white hover:bg-white/10 transition-colors"
                                title="Logout">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</div>

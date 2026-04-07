<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mebel Stock') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    @livewireStyles
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .ios-sidebar {
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%);
        }
        .ios-nav-item {
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .ios-nav-item:active {
            transform: scale(0.97);
        }
        .ios-glass-bar {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }
        .ios-content-bg {
            background: #f2f2f7;
        }
        @media (prefers-color-scheme: light) {
            .ios-content-bg { background: #f2f2f7; }
        }
    </style>
</head>
<body class="h-full ios-content-bg">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 md:hidden"
             @click="sidebarOpen = false" style="display:none;"></div>

        {{-- Mobile sidebar (full height, slides from left) --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col md:hidden" style="display:none;">
            {{-- Close button --}}
            <div class="absolute top-4 right-[-44px]">
                <button @click="sidebarOpen = false" class="w-9 h-9 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center text-white/90">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @include('layouts.partials.sidebar')
        </div>

        {{-- Desktop sidebar --}}
        <div class="hidden md:flex md:w-72 md:flex-col md:fixed md:inset-y-0">
            @include('layouts.partials.sidebar')
        </div>

        {{-- Main content --}}
        <div class="md:pl-72 flex flex-col flex-1 min-h-screen min-w-0">
            {{-- iOS-style top bar for mobile --}}
            <div class="sticky top-0 z-30 md:hidden ios-glass-bar border-b border-gray-200/60">
                <div class="flex items-center justify-between px-4 h-14">
                    <button type="button" @click="sidebarOpen = true" class="p-1.5 -ml-1.5 rounded-lg text-gray-600 hover:bg-gray-100/80 active:bg-gray-200/80 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <span class="text-[17px] font-semibold text-gray-900 tracking-tight">{{ config('app.name') }}</span>
                    <div class="w-9"></div>
                </div>
            </div>

            <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>
        </div>

        {{-- Global Toast Notification --}}
        <div x-data="{
                toasts: [],
                add(type, message) {
                    const id = Date.now();
                    this.toasts.push({ id, type, message });
                    setTimeout(() => this.remove(id), 5000);
                },
                remove(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            }"
            @notify.window="add($event.detail.type, $event.detail.message)"
            class="fixed bottom-5 right-5 z-100 flex flex-col gap-3 pointer-events-none">
            
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    class="pointer-events-auto w-80 max-w-sm rounded-xl overflow-hidden bg-white/90 backdrop-blur-xl shadow-lg ring-1 ring-black/5 flex items-start gap-0.5 relative group">
                    <div class="shrink-0 w-12 flex items-center justify-center p-3 relative" 
                        :class="toast.type === 'error' ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-500'">
                        <div class="absolute inset-y-0 right-0 w-px" :class="toast.type === 'error' ? 'bg-red-100' : 'bg-emerald-100'"></div>
                        <template x-if="toast.type === 'error'">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </template>
                        <template x-if="toast.type !== 'error'">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                    </div>
                    <div class="flex-1 p-3 px-4">
                        <p class="text-xs font-bold uppercase tracking-wider mb-0.5" :class="toast.type === 'error' ? 'text-red-700' : 'text-emerald-700'" x-text="toast.type === 'error' ? 'Proses Gagal' : 'Berhasil'"></p>
                        <p class="text-sm font-medium text-gray-700" x-text="toast.message"></p>
                    </div>
                    <button @click="remove(toast.id)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors opacity-0 group-hover:opacity-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <!-- Loading bar animation -->
                    <div class="absolute bottom-0 left-0 h-1 bg-linear-to-r" 
                        :class="toast.type === 'error' ? 'from-red-500 to-orange-400' : 'from-emerald-500 to-green-400'"
                        style="animation: shrink-toast infinite 5000ms linear;" x-ref="loader"></div>
                </div>
            </template>
        </div>
        <style>@keyframes shrink-toast { from { width: 100%; } to { width: 0%; } }</style>
    </div>
    @livewireScripts
</body>
</html>

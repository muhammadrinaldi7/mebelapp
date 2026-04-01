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
    </div>
    @livewireScripts
</body>
</html>

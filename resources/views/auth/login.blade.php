<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Mebel Stock') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="h-full antialiased" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
    <div class="min-h-full flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">

        {{-- Logo & Title --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-sm text-center">
            <div class="mx-auto w-16 h-16 rounded-3xl bg-white/15 backdrop-blur-sm flex items-center justify-center shadow-lg mb-6">
                <span class="text-3xl">📦</span>
            </div>
            <h1 class="text-[28px] font-bold text-white tracking-tight">{{ config('app.name') }}</h1>
            <p class="mt-1.5 text-sm text-indigo-200/80">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- Login Card --}}
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-7 ring-1 ring-white/20">
                <form class="space-y-5" action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-[13px] font-medium text-gray-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="block w-full rounded-xl border border-gray-200 bg-gray-50/80 px-4 py-3 text-[15px] text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all">
                        @error('email')
                            <p class="mt-1.5 text-[13px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-[13px] font-medium text-gray-700 mb-1.5">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               placeholder="••••••••"
                               class="block w-full rounded-xl border border-gray-200 bg-gray-50/80 px-4 py-3 text-[15px] text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all">
                        @error('password')
                            <p class="mt-1.5 text-[13px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded-md border-gray-300 text-indigo-600 focus:ring-indigo-500/30">
                        <label for="remember" class="ml-2.5 text-[13px] text-gray-600">Ingat saya</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full rounded-xl bg-linear-to-r from-indigo-600 to-indigo-700 px-4 py-3 text-[15px] font-semibold text-white shadow-lg shadow-indigo-500/30 hover:from-indigo-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-2 active:scale-[0.98] transition-all duration-200">
                        Masuk
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-[13px] text-indigo-200/60">
                {{ config('app.name') }} &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>

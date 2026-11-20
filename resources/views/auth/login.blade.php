@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[85vh] w-full flex items-center justify-center p-4">
    <div class="flex flex-col w-full">
        <div class="relative w-full max-w-md mx-auto my-auto flex flex-col items-center">

            {{-- Ambient glows --}}
            <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-fixed rounded-full blur-3xl opacity-40 pointer-events-none -z-10"></div>
            <div class="absolute -bottom-10 -right-10 w-60 h-60 bg-secondary-fixed rounded-full blur-3xl opacity-30 pointer-events-none -z-10"></div>

            {{-- Card --}}
            <div class="w-full bg-surface-container-lowest rounded-xl shadow-xl p-8 sm:p-10 flex flex-col">

                {{-- Brand & Header --}}
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center shadow-lg mb-4">
                        <span class="material-symbols-outlined text-2xl">bookmarks</span>
                    </div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight mb-2">Welcome back to LinkVault</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant">Sign in to manage your bookmarks.</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block font-label-md text-label-md text-on-surface">Email Address</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </div>
                            <input
                                type="email" id="email" name="email"
                                value="{{ old('email') }}"
                                required autofocus autocomplete="email"
                                placeholder="name@example.com"
                                class="w-full bg-surface-container-low text-on-surface placeholder:text-outline font-body-md text-body-md rounded-lg py-2.5 pl-10 pr-4 outline-none transition-all focus:bg-surface-container-lowest focus:shadow-md
                                    {{ $errors->has('email') ? 'ring-2 ring-error' : '' }}"
                            >
                        </div>
                        @error('email')
                            <p class="font-body-sm text-body-sm text-error flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block font-label-md text-label-md text-on-surface">Password</label>
                        </div>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <input
                                type="password" id="password" name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full bg-surface-container-low text-on-surface placeholder:text-outline font-body-md text-body-md rounded-lg py-2.5 pl-10 pr-10 outline-none transition-all focus:bg-surface-container-lowest focus:shadow-md"
                            >
                            <button
                                type="button"
                                onclick="const p=document.getElementById('password');const isPass=p.type==='password';p.type=isPass?'text':'password';this.querySelector('span').innerText=isPass?'visibility_off':'visibility';"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors"
                            >
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="remember" id="remember"
                                class="w-4 h-4 rounded text-primary-container focus:ring-primary-container cursor-pointer">
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Remember me</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md py-2.5 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                            <span>Sign in to LinkVault</span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </form>

                {{-- Register link --}}
                <div class="mt-8 text-center pt-2">
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-label-sm text-label-sm text-primary hover:text-primary-container font-semibold transition-colors ml-1">
                            Sign up for free
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

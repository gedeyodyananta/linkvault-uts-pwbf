@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-[85vh] w-full flex items-center justify-center p-4">
<div class="flex flex-col w-full">
<div class="relative w-full max-w-5xl mx-auto flex flex-col lg:flex-row items-stretch justify-center gap-8 lg:gap-12 py-6">

    {{-- Left column: value prop (desktop only) --}}
    <div class="hidden lg:flex flex-col w-full max-w-sm py-6 select-none gap-6">
            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl">bookmark_manager</span>
                </div>
                <span class="font-headline-lg text-headline-lg tracking-tight text-on-surface">LinkVault</span>
            </div>

            <div class="flex flex-col gap-3 pt-4">
                <h2 class="font-headline-xl text-headline-xl text-on-surface tracking-tight leading-tight">Organize and share your bookmarks</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">Save web links, categorize them, and share your collection with the community.</p>
            </div>
        </div>

    {{-- Right column: form --}}
    <div class="w-full max-w-xl mx-auto lg:mx-0">
        <div class="bg-surface-container-lowest rounded-xl shadow-md p-6 sm:p-10 flex flex-col gap-6">

            {{-- Header --}}
            <div class="flex flex-col gap-2 text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start gap-2.5 mb-1 lg:hidden">
                    <div class="w-8 h-8 rounded-lg bg-primary text-on-primary flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-lg">bookmark_manager</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-primary tracking-tight">LinkVault</span>
                </div>
                <h1 class="font-headline-xl text-headline-xl tracking-tight text-on-surface font-bold">Create your LinkVault account</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">Save links, organize them into categories, and share with the community.</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf

                {{-- Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="font-label-md text-label-md text-on-surface font-semibold">Full Name</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">person</span>
                        <input
                            type="text" id="name" name="name"
                            value="{{ old('name') }}"
                            required autofocus maxlength="100" autocomplete="name"
                            placeholder="Alex Rivera"
                            class="w-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant/50 font-body-md text-body-md rounded-lg pl-11 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-container transition-all
                                {{ $errors->has('name') ? 'ring-2 ring-error' : '' }}"
                            style="{{ $errors->has('name') ? '' : 'box-shadow: 0 0 0 1px #dae2fd;' }}"
                        >
                    </div>
                    @error('name')
                        <p class="font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="font-label-md text-label-md text-on-surface font-semibold">Email</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">mail</span>
                        <input
                            type="email" id="email" name="email"
                            value="{{ old('email') }}"
                            required autocomplete="email"
                            placeholder="alex@example.com"
                            class="w-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant/50 font-body-md text-body-md rounded-lg pl-11 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-container transition-all
                                {{ $errors->has('email') ? 'ring-2 ring-error' : '' }}"
                            style="{{ $errors->has('email') ? '' : 'box-shadow: 0 0 0 1px #dae2fd;' }}"
                        >
                    </div>
                    @error('email')
                        <p class="font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-1.5">
                    <label for="password" class="font-label-md text-label-md text-on-surface font-semibold">Create a Password</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">lock</span>
                        <input
                            type="password" id="password" name="password"
                            required minlength="8" autocomplete="new-password"
                            placeholder="••••••••••••"
                            class="w-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant/50 font-body-md text-body-md rounded-lg pl-11 pr-11 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-container transition-all
                                {{ $errors->has('password') ? 'ring-2 ring-error' : '' }}"
                            style="{{ $errors->has('password') ? '' : 'box-shadow: 0 0 0 1px #dae2fd;' }}"
                        >
                        <button type="button" id="toggle-pwd"
                            class="absolute right-3.5 text-on-surface-variant hover:text-on-surface flex items-center justify-center p-1 rounded transition-colors">
                            <span class="material-symbols-outlined text-lg" id="pwd-icon">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="font-body-sm text-body-sm text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm password --}}
                <div class="flex flex-col gap-1.5">
                    <label for="password_confirmation" class="font-label-md text-label-md text-on-surface font-semibold">Confirm Password</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">lock_reset</span>
                        <input
                            type="password" id="password_confirmation" name="password_confirmation"
                            required autocomplete="new-password"
                            placeholder="••••••••••••"
                            class="w-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant/50 font-body-md text-body-md rounded-lg pl-11 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-container transition-all"
                            style="box-shadow: 0 0 0 1px #dae2fd;"
                        >
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full mt-2 py-3 px-6 rounded-lg bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group active:scale-[0.99]">
                    <span>Create Account</span>
                    <span class="material-symbols-outlined text-lg transition-transform group-hover:translate-x-1">arrow_forward</span>
                </button>
            </form>

            {{-- Login link --}}
            <div class="flex items-center justify-center gap-1.5 text-center">
                <span class="font-body-md text-body-md text-on-surface-variant">Already have an account?</span>
                <a href="{{ route('login') }}" class="font-label-md text-label-md font-semibold text-primary hover:underline transition-colors">Log in</a>
            </div>
        </div>

</div>
</div>
</div>

<script>
(function() {
    const toggleBtn = document.getElementById('toggle-pwd');
    const pwdInput = document.getElementById('password');
    const pwdIcon = document.getElementById('pwd-icon');
    if (toggleBtn && pwdInput && pwdIcon) {
        toggleBtn.addEventListener('click', function() {
            const isPassword = pwdInput.type === 'password';
            pwdInput.type = isPassword ? 'text' : 'password';
            pwdIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
        });
    }
})();
</script>

@endsection

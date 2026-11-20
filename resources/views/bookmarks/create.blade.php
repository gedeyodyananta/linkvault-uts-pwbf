@extends('layouts.app')

@section('title', 'Add Bookmark')

@section('content')
<div class="flex flex-col w-full">
<div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    {{-- Ambient glows --}}
    <div class="absolute -top-12 right-12 w-96 h-96 bg-primary-container/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-1/2 -left-20 w-80 h-80 bg-secondary-container/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 font-label-md text-label-md text-on-surface-variant mb-6">
        <a href="{{ route('explore') }}" class="hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">home</span><span>Home</span>
        </a>
        <span class="material-symbols-outlined text-xs text-outline-variant">chevron_right</span>
        <a href="{{ route('bookmarks.index') }}" class="hover:text-primary transition-colors">My Bookmarks</a>
        <span class="material-symbols-outlined text-xs text-outline-variant">chevron_right</span>
        <span class="text-on-surface font-semibold">Add Bookmark</span>
    </nav>

    {{-- Main grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Form card --}}
        <section class="lg:col-span-8 bg-surface-container-lowest rounded-2xl p-6 sm:p-10 shadow-xl shadow-on-surface/5">

            {{-- Header --}}
            <div class="flex items-start justify-between pb-8">
                <div>
                    <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">Add Bookmark</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1.5">Save a web page, article, or tool to your collection.</p>
                </div>
                <div class="hidden sm:flex items-center justify-center w-12 h-12 rounded-xl bg-surface-container text-primary">
                    <span class="material-symbols-outlined text-2xl">add_link</span>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('bookmarks.store') }}" class="space-y-6">
                @csrf

                {{-- URL --}}
                <div class="space-y-2">
                    <label for="url" class="font-label-md text-label-md font-semibold text-on-surface flex items-center gap-1">
                        URL <span class="text-error">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant pointer-events-none text-xl">link</span>
                        <input
                            type="url"
                            id="url"
                            name="url"
                            value="{{ old('url') }}"
                            required
                            placeholder="https://example.com/article-or-tool"
                            class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md placeholder:text-on-surface-variant rounded-xl pl-11 pr-4 py-3.5 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-container
                                {{ $errors->has('url') ? 'ring-2 ring-error' : '' }}"
                        >
                    </div>
                    @error('url')
                        <p class="text-error font-body-sm text-body-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Title --}}
                <div class="space-y-2">
                    <label for="title" class="font-label-md text-label-md font-semibold text-on-surface flex items-center gap-1">
                        Title <span class="text-error">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant pointer-events-none text-xl">title</span>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            maxlength="100"
                            autofocus
                            placeholder="e.g., Tailwind CSS Modern Architecture Guide"
                            class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md placeholder:text-on-surface-variant rounded-xl pl-11 pr-4 py-3.5 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-container
                                {{ $errors->has('title') ? 'ring-2 ring-error' : '' }}"
                        >
                    </div>
                    @error('title')
                        <p class="text-error font-body-sm text-body-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="space-y-2">
                    <label for="category" class="font-label-md text-label-md font-semibold text-on-surface flex items-center gap-1">
                        Category
                    </label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant pointer-events-none text-xl">folder_open</span>
                        <select
                            id="category"
                            name="category"
                            class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-11 pr-10 py-3.5 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-container appearance-none cursor-pointer
                                {{ $errors->has('category') ? 'ring-2 ring-error' : '' }}"
                        >
                            <option value="">Select a category...</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3.5 text-on-surface-variant pointer-events-none text-xl">expand_more</span>
                    </div>
                    @error('category')
                        <p class="text-error font-body-sm text-body-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label for="description" class="font-label-md text-label-md font-semibold text-on-surface">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="500"
                        placeholder="Write notes, key takeaways, or context about why you saved this bookmark..."
                        class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md placeholder:text-on-surface-variant rounded-xl p-3.5 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-container resize-y
                            {{ $errors->has('description') ? 'ring-2 ring-error' : '' }}"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-error font-body-sm text-body-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Visibility toggle --}}
                <div class="bg-surface-container rounded-2xl p-5 transition-all" id="visibilityCard">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="p-2.5 rounded-xl bg-surface-container-highest text-on-surface-variant mt-0.5 transition-colors" id="visibilityIconContainer">
                                <span class="material-symbols-outlined text-2xl" id="visibilityIcon">lock</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <label for="publicToggle" class="font-headline-sm text-headline-sm text-on-surface cursor-pointer select-none">
                                        Make this bookmark Public
                                    </label>
                                    <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container-highest text-on-surface-variant" id="visibilityBadge">
                                        Private Vault Only
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 leading-relaxed">
                                    When enabled, this link will be visible to everyone on the Explore page.
                                </p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                            <input type="hidden" name="is_public" value="0">
                            <input
                                type="checkbox"
                                id="publicToggle"
                                name="is_public"
                                value="1"
                                {{ old('is_public') ? 'checked' : '' }}
                                onchange="handleVisibilityToggle(this)"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-surface-container-lowest after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-surface-container-lowest after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container shadow-inner"></div>
                        </label>
                    </div>
                </div>

                {{-- Favorite toggle --}}
                <div class="p-4 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between">
                    <label for="is_favorite" class="flex items-center gap-3 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            id="is_favorite"
                            name="is_favorite"
                            value="1"
                            {{ old('is_favorite') ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-primary-container focus:ring-primary-container transition-colors cursor-pointer"
                        >
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500 text-xl" style="font-variation-settings:'FILL' 1;">star</span>
                            <span class="font-label-md text-label-md font-semibold text-on-surface">Mark as Favorite</span>
                        </div>
                    </label>
                    <span class="font-body-sm text-body-sm text-on-surface-variant hidden sm:inline">Pins to top of your dashboard</span>
                </div>

                {{-- Actions --}}
                <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('bookmarks.index') }}"
                       class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors flex items-center gap-1 order-3 sm:order-1">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-md text-label-md shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 active:scale-[0.98] order-1 sm:order-2">
                        <span class="material-symbols-outlined text-lg">bookmark</span>
                        Save Bookmark
                    </button>
                </div>
            </form>
        </section>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4 space-y-6">

            {{-- Vault stats --}}
            <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Your Vault</span>
                        <h4 class="font-headline-sm text-headline-sm text-on-surface">{{ $totalBookmarks }} Links Saved</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">data_usage</span>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 bg-surface-container-low rounded-xl">
                        <span class="font-headline-sm text-headline-sm text-on-surface">{{ count($categories) }}</span>
                        <span class="block font-label-sm text-label-sm text-on-surface-variant mt-0.5">Categories</span>
                    </div>
                    <div class="p-3 bg-surface-container-low rounded-xl">
                        <span class="font-headline-sm text-headline-sm text-on-surface">{{ $favoritesCount }}</span>
                        <span class="block font-label-sm text-label-sm text-on-surface-variant mt-0.5">Favorites</span>
                    </div>
                </div>
            </div>

            {{-- Recently added --}}
            @if ($recentBookmarks->isNotEmpty())
                <div class="bg-surface-container-lowest rounded-2xl p-6 shadow-sm">
                    <h4 class="font-headline-sm text-headline-sm text-on-surface mb-3 flex items-center justify-between">
                        <span>Recently Added</span>
                        <span class="material-symbols-outlined text-on-surface-variant text-base">history</span>
                    </h4>
                    <div class="space-y-3">
                        @foreach ($recentBookmarks as $recent)
                            <a href="{{ $recent->url }}" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant group-hover:text-primary shrink-0">
                                    <span class="material-symbols-outlined text-base">link</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-label-md text-label-md text-on-surface truncate group-hover:text-primary transition-colors">{{ $recent->title }}</p>
                                    <span class="font-mono-code text-mono-code text-on-surface-variant">{{ $recent->domain }} &bull; {{ $recent->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </aside>
    </div>
</div>
</div>

<script>
function handleVisibilityToggle(checkbox) {
    const iconContainer = document.getElementById('visibilityIconContainer');
    const icon = document.getElementById('visibilityIcon');
    const badge = document.getElementById('visibilityBadge');
    if (checkbox.checked) {
        iconContainer.className = 'p-2.5 rounded-xl bg-secondary-container/20 text-secondary mt-0.5 transition-colors';
        icon.textContent = 'public';
        badge.className = 'px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-secondary-fixed text-on-secondary-fixed font-medium';
        badge.textContent = 'Public Community Feed';
    } else {
        iconContainer.className = 'p-2.5 rounded-xl bg-surface-container-highest text-on-surface-variant mt-0.5 transition-colors';
        icon.textContent = 'lock';
        badge.className = 'px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container-highest text-on-surface-variant';
        badge.textContent = 'Private Vault Only';
    }
}

// Set initial state if old value was checked
@if(old('is_public'))
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('publicToggle');
    if (toggle && toggle.checked) handleVisibilityToggle(toggle);
});
@endif
</script>

@endsection

@extends('layouts.app')

@section('title', 'Explore — LinkVault')

@section('content')
<div class="flex flex-col w-full">

    {{-- Ambient background --}}
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-8 overflow-hidden">
        <div class="absolute -top-32 -left-20 w-96 h-96 bg-primary-fixed/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-48 right-0 w-80 h-80 bg-secondary-fixed/30 rounded-full blur-3xl pointer-events-none -z-10"></div>

        {{-- Page header --}}
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-surface-container">
            <div class="max-w-2xl space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">explore</span>
                    Public Bookmarks
                </div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">Community Explore</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Discover developer tools, design resources, articles, and libraries curated by the LinkVault community.</p>
            </div>
            <div class="flex items-center gap-4 bg-surface-container-lowest p-3 rounded-xl shadow-sm self-start md:self-auto">
                <div class="leading-tight">
                    <span class="font-label-md text-label-md text-on-surface font-semibold block">{{ $bookmarks->count() }} public links</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">from the community</span>
                </div>
            </div>
        </header>

        {{-- Search + filter toolbar --}}
        <section class="mt-8 space-y-5">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <div class="relative flex-1 max-w-2xl">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-xl">search</span>
                    <input
                        class="w-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant font-body-md text-body-md rounded-xl pl-11 pr-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                        id="searchInput"
                        placeholder="Filter by title, domain, or category..."
                        type="text"
                        value="{{ $search }}"
                    >
                </div>
                <form method="GET" action="{{ route('explore') }}" class="flex items-center gap-3">
                    <select name="category"
                        onchange="this.form.submit()"
                        class="px-3 py-2.5 font-label-md text-label-md bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @if ($selectedCategory !== '')
                        <a href="{{ route('explore') }}"
                           class="px-3.5 py-2.5 font-label-md text-label-md bg-surface-container-lowest hover:bg-surface-container text-on-surface-variant rounded-xl shadow-sm transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </section>

        {{-- Cards grid --}}
        @php
            $avatarColors = [
                'bg-primary-fixed text-on-primary-fixed',
                'bg-secondary-fixed text-on-secondary-fixed',
                'bg-tertiary-fixed text-on-tertiary-fixed',
                'bg-surface-container-high text-primary',
            ];
        @endphp
        @if ($bookmarks->isEmpty())
            <div class="mt-16 text-center py-16">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant" style="font-variation-settings:'FILL' 1;">bookmarks</span>
                <p class="mt-4 font-headline-sm text-headline-sm text-on-surface-variant">
                    {{ $search !== '' || $selectedCategory !== '' ? 'No bookmarks match your search.' : 'No public bookmarks yet.' }}
                </p>
                @if ($search !== '' || $selectedCategory !== '')
                    <a href="{{ route('explore') }}" class="mt-3 inline-block font-label-md text-label-md text-primary hover:underline">Clear filters</a>
                @endif
            </div>
        @else
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="cardsContainer">
                @foreach ($bookmarks as $bookmark)
                    @php
                        $colorClass = $avatarColors[$bookmark->user->id % count($avatarColors)];
                        $initial = strtoupper(substr($bookmark->user->name, 0, 1));
                    @endphp
                    <article class="bookmark-card group bg-surface-container-lowest rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between"
                             data-category="{{ strtolower($bookmark->category ?? 'other') }}"
                             data-title="{{ strtolower($bookmark->title) }}"
                             data-domain="{{ strtolower($bookmark->domain) }}">
                        <div class="space-y-4">
                            {{-- Domain + date --}}
                            <div class="flex items-center justify-between">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                    <span class="material-symbols-outlined text-sm text-secondary">verified</span>
                                    <span class="font-medium text-on-surface">{{ $bookmark->domain }}</span>
                                </div>
                                <time class="font-label-sm text-label-sm text-on-surface-variant">{{ $bookmark->created_at->diffForHumans() }}</time>
                            </div>

                            {{-- Title + description --}}
                            <div>
                                <a class="group-hover:text-primary transition-colors flex items-start justify-between gap-2"
                                   href="{{ $bookmark->url }}" target="_blank" rel="noopener noreferrer">
                                    <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold line-clamp-1">{{ $bookmark->title }}</h2>
                                    <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary shrink-0 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">arrow_outward</span>
                                </a>
                                @if ($bookmark->description)
                                    <p class="mt-2 font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ $bookmark->description }}</p>
                                @endif
                            </div>

                            {{-- Category tag --}}
                            @if ($bookmark->category)
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md font-label-sm text-label-sm bg-primary-fixed text-primary font-medium">#{{ strtolower($bookmark->category) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Card footer --}}
                        <div class="pt-5 mt-4 border-t border-surface-container flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <a href="{{ route('users.show', $bookmark->user) }}"
                                   class="w-6 h-6 rounded-full {{ $colorClass }} font-bold font-label-sm text-label-sm flex items-center justify-center shrink-0 hover:ring-2 hover:ring-primary/30 transition-all">
                                    {{ $initial }}
                                </a>
                                <a href="{{ route('users.show', $bookmark->user) }}"
                                   class="font-label-sm text-label-sm text-on-surface-variant hover:text-on-surface transition-colors truncate">
                                    {{ $bookmark->user->name }}
                                </a>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                @auth
                                    @if ($bookmark->user_id !== auth()->id())
                                        <form method="POST" action="{{ route('explore.save', $bookmark) }}">
                                            @csrf
                                            <button type="submit"
                                                class="save-btn p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors"
                                                title="Save to My Collection">
                                                <span class="material-symbols-outlined text-base">bookmark_add</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="font-label-sm text-label-sm text-on-surface-variant italic px-2">Your link</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors"
                                       title="Login to save">
                                        <span class="material-symbols-outlined text-base">bookmark_border</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

    </div>
</div>

<script>
(() => {
    // Client-side search by title/domain
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.bookmark-card').forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const domain = card.getAttribute('data-domain') || '';
                const visible = title.includes(query) || domain.includes(query) || query === '';
                card.style.display = visible ? '' : 'none';
            });
        });
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                searchInput.value = '';
                document.querySelectorAll('.bookmark-card').forEach(c => c.style.display = '');
            }
        });
    }
})();
</script>

@endsection

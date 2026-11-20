@extends('layouts.app')

@section('title', 'My Bookmarks')

@section('content')

{{-- Sub-header / Metrics Banner --}}
<section class="w-full bg-surface-container-low px-6 lg:px-12 py-8">
    <div class="max-w-7xl mx-auto flex flex-col gap-6">

        {{-- Vault Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="w-12 h-12 rounded-xl bg-primary-container text-on-primary flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-2xl">bookmarks</span>
                </div>
                <div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">My Bookmarks</h1>
                </div>
            </div>
            <a href="{{ route('bookmarks.create') }}"
               class="inline-flex items-center gap-2 bg-primary-container hover:bg-primary text-on-primary font-label-md text-label-md px-4 py-2 rounded-lg shadow-sm transition-all active:scale-[0.98]">
                <span class="material-symbols-outlined text-lg">add_link</span>
                + Add Bookmark
            </a>
        </div>

        {{-- Quick Metrics Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Links</span>
                    <span class="font-headline-lg text-headline-lg font-bold text-on-surface mt-1">{{ $totalBookmarks }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">folder_open</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Favorites</span>
                    <span class="font-headline-lg text-headline-lg font-bold text-on-surface mt-1">{{ $favoritesCount }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-tertiary-fixed text-tertiary flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">star</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Private</span>
                    <span class="font-headline-lg text-headline-lg font-bold text-on-surface mt-1">{{ $privateCount }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-surface-container text-on-surface-variant flex items-center justify-center">
                    <span class="material-symbols-outlined">lock</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Public</span>
                    <span class="font-headline-lg text-headline-lg font-bold text-on-surface mt-1">{{ $publicCount }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-secondary-fixed text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined">public</span>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Main Content --}}
<div class="w-full max-w-7xl mx-auto px-6 lg:px-12 py-8 flex flex-col gap-8">

    {{-- Favorites / Pinned Section --}}
    @if ($favorites->isNotEmpty() && $search === '' && $selectedCategory === '')
        <section class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings:'FILL' 1;">push_pin</span>
                <h2 class="font-headline-sm text-headline-sm text-on-surface">Favorites</h2>
                <span class="text-xs font-mono-code bg-surface-container px-2 py-0.5 rounded text-on-surface-variant">{{ $favorites->count() }} pinned</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($favorites->take(3) as $bookmark)
                    <div class="group bg-surface-container-lowest p-4 rounded-xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 left-0 h-1 w-full bg-primary-container"></div>
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2 pt-1">
                                <span class="font-mono-code text-mono-code text-on-surface-variant truncate">{{ $bookmark->domain }}</span>
                                @if ($bookmark->is_public)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-secondary-fixed text-on-secondary-fixed-variant">
                                        <span class="material-symbols-outlined text-xs">public</span> Public
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-label-sm text-label-sm bg-surface-container-low text-on-surface-variant">
                                        <span class="material-symbols-outlined text-xs">lock</span> Private
                                    </span>
                                @endif
                            </div>
                            <a class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors line-clamp-1 block"
                               href="{{ $bookmark->url }}" target="_blank" rel="noopener noreferrer">
                                {{ $bookmark->title }}
                            </a>
                            @if ($bookmark->description)
                                <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 mt-1">{{ $bookmark->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $bookmark->created_at->format('M j, Y') }}</span>
                            <div class="flex items-center gap-1">
                                <a href="{{ $bookmark->url }}" target="_blank" rel="noopener noreferrer"
                                   class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded transition-colors" title="Open Link">
                                    <span class="material-symbols-outlined text-base">open_in_new</span>
                                </a>
                                <a href="{{ route('bookmarks.edit', $bookmark) }}"
                                   class="p-1.5 text-on-surface-variant hover:text-on-surface hover:bg-surface-container rounded transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Filter & Search Controls --}}
    <section class="bg-surface-container-lowest p-5 rounded-2xl shadow-sm flex flex-col gap-4">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            {{-- Search --}}
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input
                    class="w-full bg-surface-container-low text-on-surface placeholder:text-on-surface-variant font-body-sm text-body-sm rounded-xl pl-10 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-container/20"
                    id="bookmark-search-input"
                    placeholder="Filter by title, domain, or description..."
                    type="text"
                    value="{{ $search }}"
                >
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface p-1 rounded transition-colors {{ $search ? '' : 'hidden' }}"
                        id="clear-search-btn" type="button" aria-label="Clear Search">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>

            {{-- View toggle --}}
            <div class="flex items-center gap-1 bg-surface-container-low p-1 rounded-xl self-end lg:self-auto">
                <button aria-label="Grid View" class="p-1.5 rounded-lg bg-surface-container-lowest text-primary shadow-sm transition-all" id="view-grid-btn" type="button">
                    <span class="material-symbols-outlined text-lg">grid_view</span>
                </button>
                <button aria-label="List View" class="p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface transition-all" id="view-list-btn" type="button">
                    <span class="material-symbols-outlined text-lg">view_list</span>
                </button>
            </div>
        </div>

        {{-- Category filter --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
            <form method="GET" action="{{ route('bookmarks.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <select name="category" onchange="this.form.submit()"
                        class="appearance-none bg-surface-container-low text-on-surface font-label-sm text-label-sm rounded-lg pl-3 pr-8 py-2 focus:outline-none cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-base">expand_more</span>
                </div>
                @if ($selectedCategory !== '')
                    <a href="{{ route('bookmarks.index', $search ? ['search' => $search] : []) }}"
                       class="font-label-sm text-label-sm text-primary hover:underline">Clear filter</a>
                @endif
            </form>
            <span class="font-body-sm text-body-sm text-on-surface-variant ml-auto">
                Showing <span class="font-semibold text-on-surface">{{ $bookmarks->count() }}</span> of {{ $totalBookmarks }} links
            </span>
        </div>
    </section>

    {{-- Bookmark Cards --}}
    @if ($bookmarks->isEmpty())
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant" style="font-variation-settings:'FILL' 1;">bookmarks</span>
            @if ($search !== '' || $selectedCategory !== '')
                <p class="mt-4 font-headline-sm text-headline-sm text-on-surface-variant">No bookmarks match your search.</p>
                <a href="{{ route('bookmarks.index') }}" class="mt-3 inline-block font-label-md text-label-md text-primary hover:underline">Clear filters</a>
            @else
                <p class="mt-4 font-headline-sm text-headline-sm text-on-surface-variant">Your vault is empty.</p>
                <a href="{{ route('bookmarks.create') }}"
                   class="mt-4 inline-flex items-center gap-2 bg-primary-container hover:bg-primary text-on-primary font-label-md text-label-md px-4 py-2 rounded-lg shadow-sm transition-all">
                    <span class="material-symbols-outlined text-lg">add_link</span>
                    Add your first bookmark
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="bookmarks-container">
            @foreach ($bookmarks as $bookmark)
                <article class="bookmark-card group relative bg-surface-container-lowest rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between"
                         data-title="{{ strtolower($bookmark->title) }}"
                         data-domain="{{ strtolower($bookmark->domain) }}"
                         data-description="{{ strtolower($bookmark->description ?? '') }}">
                    <div>
                        {{-- Card header: domain badge + initials --}}
                        <div class="w-full bg-surface-container-low rounded-xl p-4 mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-surface-container-highest text-primary flex items-center justify-center font-bold font-mono-code text-sm uppercase">
                                    {{ substr($bookmark->domain, 0, 3) }}
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-label-sm text-label-sm text-on-surface font-semibold line-clamp-1">{{ $bookmark->title }}</span>
                                    <span class="font-mono-code text-mono-code text-on-surface-variant truncate">{{ $bookmark->domain }}</span>
                                </div>
                            </div>
                            {{-- Favorite toggle --}}
                            <form method="POST" action="{{ route('bookmarks.favorite', $bookmark) }}">
                                @csrf
                                <button type="submit"
                                    class="star-toggle-btn {{ $bookmark->is_favorite ? 'text-amber-500' : 'text-on-surface-variant' }} hover:scale-110 transition-transform"
                                    title="{{ $bookmark->is_favorite ? 'Remove favorite' : 'Add to favorites' }}">
                                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' {{ $bookmark->is_favorite ? '1' : '0' }}">star</span>
                                </button>
                            </form>
                        </div>

                        {{-- Category + visibility --}}
                        <div class="flex items-center justify-between gap-2 mb-2">
                            @if ($bookmark->category)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container text-primary">
                                    {{ $bookmark->category }}
                                </span>
                            @else
                                <span></span>
                            @endif

                            {{-- Public toggle --}}
                            <form method="POST" action="{{ route('bookmarks.public', $bookmark) }}">
                                @csrf
                                <button type="submit"
                                    class="visibility-toggle-btn inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm transition-colors
                                        {{ $bookmark->is_public
                                            ? 'bg-secondary-fixed text-on-secondary-fixed-variant hover:bg-secondary-fixed-dim'
                                            : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                                    <span class="material-symbols-outlined text-xs">{{ $bookmark->is_public ? 'public' : 'lock' }}</span>
                                    <span>{{ $bookmark->is_public ? 'Public' : 'Private' }}</span>
                                </button>
                            </form>
                        </div>

                        {{-- Description --}}
                        @if ($bookmark->description)
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 mb-4">{{ $bookmark->description }}</p>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="pt-3 flex items-center justify-between text-on-surface-variant border-t border-surface-container mt-3">
                        <div class="flex items-center gap-3 font-mono-code text-mono-code">
                            <span>{{ $bookmark->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ $bookmark->url }}" target="_blank" rel="noopener noreferrer"
                               class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded-lg transition-colors" title="Open Link">
                                <span class="material-symbols-outlined text-base">open_in_new</span>
                            </a>
                            <a href="{{ route('bookmarks.edit', $bookmark) }}"
                               class="p-1.5 text-on-surface-variant hover:text-on-surface hover:bg-surface-container rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <form method="POST" action="{{ route('bookmarks.destroy', $bookmark) }}"
                                onsubmit="return confirm('Delete &quot;{{ addslashes($bookmark->title) }}&quot;?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 text-on-surface-variant hover:text-error hover:bg-surface-container rounded-lg transition-colors" title="Delete">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

</div>

<script>
(function() {
    // Client-side search
    const searchInput = document.getElementById('bookmark-search-input');
    const clearBtn = document.getElementById('clear-search-btn');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            document.querySelectorAll('.bookmark-card').forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const domain = card.getAttribute('data-domain') || '';
                const desc = card.getAttribute('data-description') || '';
                card.style.display = (title.includes(q) || domain.includes(q) || desc.includes(q) || q === '') ? '' : 'none';
            });
            if (clearBtn) clearBtn.classList.toggle('hidden', q.length === 0);
        });
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                document.querySelectorAll('.bookmark-card').forEach(c => c.style.display = '');
                searchInput.focus();
            });
        }
    }

    // Grid / list view toggle
    const gridBtn = document.getElementById('view-grid-btn');
    const listBtn = document.getElementById('view-list-btn');
    const container = document.getElementById('bookmarks-container');
    if (gridBtn && listBtn && container) {
        gridBtn.addEventListener('click', () => {
            gridBtn.className = 'p-1.5 rounded-lg bg-surface-container-lowest text-primary shadow-sm transition-all';
            listBtn.className = 'p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface transition-all';
            container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
        });
        listBtn.addEventListener('click', () => {
            listBtn.className = 'p-1.5 rounded-lg bg-surface-container-lowest text-primary shadow-sm transition-all';
            gridBtn.className = 'p-1.5 rounded-lg text-on-surface-variant hover:text-on-surface transition-all';
            container.className = 'flex flex-col gap-3';
        });
    }
})();
</script>

@endsection

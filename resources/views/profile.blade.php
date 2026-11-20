@extends('layouts.app')

@section('title', $user->name . "'s Bookmarks")

@section('content')
<div class="flex flex-col w-full">
<div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Ambient glows --}}
    <div class="absolute -top-12 right-0 w-96 h-96 bg-primary-fixed/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    {{-- Back --}}
    <a href="{{ route('explore') }}"
       class="inline-flex items-center gap-1.5 font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors mb-6">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        Back to Explore
    </a>

    {{-- Profile header --}}
    <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm mb-6">
        <div class="flex items-start gap-5 flex-wrap">
            {{-- Avatar --}}
            <div class="w-16 h-16 rounded-2xl bg-primary-container text-on-primary flex items-center justify-center text-2xl font-bold font-headline-lg shadow-sm shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">{{ $user->name }}</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-0.5">
                    {{ $bookmarks->count() }} public bookmark{{ $bookmarks->count() !== 1 ? 's' : '' }} shared with the community
                </p>
                <div class="flex items-center gap-3 mt-3 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm">public</span>
                        Public profile
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        Joined {{ $user->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bookmarks --}}
    @if ($bookmarks->isEmpty())
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant" style="font-variation-settings:'FILL' 1;">bookmarks</span>
            <p class="mt-4 font-headline-sm text-headline-sm text-on-surface-variant">{{ $user->name }} has no public bookmarks yet.</p>
            <a href="{{ route('explore') }}" class="mt-3 inline-block font-label-md text-label-md text-primary hover:underline">
                Back to Explore
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($bookmarks as $bookmark)
                <article class="group bg-surface-container-lowest rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary/20 border border-transparent transition-all duration-200 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                <span class="material-symbols-outlined text-sm text-secondary">verified</span>
                                <span class="font-medium text-on-surface">{{ $bookmark->domain }}</span>
                            </div>
                            <time class="font-label-sm text-label-sm text-on-surface-variant">{{ $bookmark->created_at->diffForHumans() }}</time>
                        </div>

                        <div>
                            <a href="{{ $bookmark->url }}" target="_blank" rel="noopener noreferrer"
                               class="group-hover:text-primary transition-colors flex items-start justify-between gap-2">
                                <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold line-clamp-1">{{ $bookmark->title }}</h2>
                                <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary shrink-0 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5">arrow_outward</span>
                            </a>
                            @if ($bookmark->description)
                                <p class="mt-1.5 font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ $bookmark->description }}</p>
                            @endif
                        </div>

                        @if ($bookmark->category)
                            <span class="inline-block px-2 py-0.5 rounded-md font-label-sm text-label-sm bg-primary-fixed text-primary font-medium">
                                #{{ strtolower($bookmark->category) }}
                            </span>
                        @endif
                    </div>

                    <div class="pt-4 mt-3 border-t border-surface-container flex items-center justify-between">
                        <span class="font-mono-code text-mono-code text-on-surface-variant">{{ $bookmark->created_at->format('M j, Y') }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ $bookmark->url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1 text-xs px-3 py-1.5 border border-surface-container text-on-surface-variant rounded-lg hover:border-slate-300 hover:bg-surface-container transition-all font-medium">
                                <span class="material-symbols-outlined text-sm">open_in_new</span> Open
                            </a>
                            @auth
                                @if ($bookmark->user_id !== auth()->id())
                                    <form method="POST" action="{{ route('explore.save', $bookmark) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs px-3 py-1.5 bg-primary-container hover:bg-primary text-on-primary rounded-lg transition-colors font-medium">
                                            <span class="material-symbols-outlined text-sm">bookmark_add</span> Save
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-1 text-xs px-3 py-1.5 border border-surface-container text-on-surface-variant rounded-lg hover:bg-surface-container transition-all font-medium">
                                    <span class="material-symbols-outlined text-sm">bookmark_border</span> Save Link
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
@endsection

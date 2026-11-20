<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $selectedCategory = $request->query('category', '');

        // Load all user bookmarks once; derive counts from the collection.
        $allBookmarks = Auth::user()->bookmarks()->orderByDesc('is_favorite')->orderByDesc('created_at')->get();

        $bookmarks = $allBookmarks
            ->when($search !== '', fn ($c) => $c->filter(fn ($b) => stripos($b->title, $search) !== false))
            ->when($selectedCategory !== '', fn ($c) => $c->where('category', $selectedCategory));

        return view('bookmarks.index', [
            'bookmarks' => $bookmarks,
            'favorites' => $allBookmarks->where('is_favorite', true),
            'search' => $search,
            'selectedCategory' => $selectedCategory,
            'categories' => Bookmark::CATEGORIES,
            'totalBookmarks' => $allBookmarks->count(),
            'favoritesCount' => $allBookmarks->where('is_favorite', true)->count(),
            'privateCount' => $allBookmarks->where('is_public', false)->count(),
            'publicCount' => $allBookmarks->where('is_public', true)->count(),
        ]);
    }

    public function create(): View
    {
        $categories = Bookmark::CATEGORIES;
        $user = Auth::user();

        $totalBookmarks = $user->bookmarks()->count();
        $favoritesCount = $user->bookmarks()->where('is_favorite', true)->count();
        $recentBookmarks = $user->bookmarks()->latest()->take(3)->get();

        return view('bookmarks.create', compact(
            'categories',
            'totalBookmarks',
            'favoritesCount',
            'recentBookmarks',
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedBookmarkData($request);

        Auth::user()->bookmarks()->create($validated);

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark saved successfully.');
    }

    public function edit(Bookmark $bookmark): View
    {
        $this->authorizeOwnership($bookmark);

        $categories = Bookmark::CATEGORIES;

        return view('bookmarks.edit', compact('bookmark', 'categories'));
    }

    public function update(Request $request, Bookmark $bookmark): RedirectResponse
    {
        $this->authorizeOwnership($bookmark);

        $validated = $this->validatedBookmarkData($request);

        $bookmark->update($validated);

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark updated successfully.');
    }

    public function destroy(Bookmark $bookmark): RedirectResponse
    {
        $this->authorizeOwnership($bookmark);

        $bookmark->delete();

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark deleted successfully.');
    }

    public function toggleFavorite(Bookmark $bookmark): RedirectResponse
    {
        $this->authorizeOwnership($bookmark);

        $bookmark->update(['is_favorite' => ! $bookmark->is_favorite]);

        return back()->with('success', $bookmark->is_favorite
            ? 'Added to favorites.'
            : 'Removed from favorites.'
        );
    }

    public function togglePublic(Bookmark $bookmark): RedirectResponse
    {
        $this->authorizeOwnership($bookmark);

        $bookmark->update(['is_public' => ! $bookmark->is_public]);

        return back()->with('success', $bookmark->is_public
            ? 'Bookmark is now public.'
            : 'Bookmark is now private.'
        );
    }

    private function authorizeOwnership(Bookmark $bookmark): void
    {
        if ($bookmark->user_id !== Auth::id()) {
            abort(403, 'This bookmark does not belong to you.');
        }
    }

    /**
     * @return array{title: string, url: string, description: string|null, category: string|null, is_public: bool, is_favorite: bool}
     */
    private function validatedBookmarkData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:2048'],
            'description' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'in:'.implode(',', Bookmark::CATEGORIES)],
            'is_public' => ['boolean'],
        ]);

        $validated['is_favorite'] = $request->boolean('is_favorite');

        return $validated;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExploreController extends Controller
{
    /**
     * Show all public bookmarks from all users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $selectedCategory = $request->query('category', '');
        $categories = Bookmark::CATEGORIES;

        $bookmarks = Bookmark::with('user')
            ->where('is_public', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->when($selectedCategory !== '', function ($query) use ($selectedCategory) {
                $query->where('category', $selectedCategory);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('explore', compact('bookmarks', 'search', 'selectedCategory', 'categories'));
    }

    /**
     * Copy a public bookmark into the authenticated user's collection.
     */
    public function save(Request $request, Bookmark $bookmark): RedirectResponse
    {
        if (! $bookmark->is_public) {
            abort(403, 'This bookmark is not public.');
        }

        Auth::user()->bookmarks()->create([
            'title' => $bookmark->title,
            'url' => $bookmark->url,
            'description' => $bookmark->description,
            'category' => $bookmark->category,
            'is_favorite' => false,
            'is_public' => false,
        ]);

        return redirect()->route('bookmarks.index')
            ->with('success', '"'.$bookmark->title.'" saved to your collection.');
    }
}

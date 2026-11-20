<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    /**
     * Show all public bookmarks belonging to the given user.
     */
    public function show(User $user): View
    {
        $bookmarks = $user->bookmarks()
            ->where('is_public', true)
            ->orderByDesc('created_at')
            ->get();

        return view('profile', compact('user', 'bookmarks'));
    }
}

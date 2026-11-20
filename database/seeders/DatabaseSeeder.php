<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(4)->create()->each(function (User $user) {
            $user->bookmarks()->createMany(
                Bookmark::factory(6)->make()->toArray()
            );
        });
    }
}

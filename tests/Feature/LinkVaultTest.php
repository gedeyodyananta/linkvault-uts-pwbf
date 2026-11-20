<?php

namespace Tests\Feature;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkVaultTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function makeUser(): User
    {
        return User::factory()->create();
    }

    private function makeBookmark(User $user, array $attrs = []): Bookmark
    {
        return Bookmark::factory()->create(array_merge(['user_id' => $user->id], $attrs));
    }

    // ─── Explore (public) ────────────────────────────────────────────────────

    public function test_explore_page_loads_for_guests(): void
    {
        $this->get(route('explore'))->assertStatus(200);
    }

    public function test_explore_shows_only_public_bookmarks(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'Public Link', 'is_public' => true]);
        $this->makeBookmark($user, ['title' => 'Private Link', 'is_public' => false]);

        $this->get(route('explore'))
            ->assertSee('Public Link')
            ->assertDontSee('Private Link');
    }

    public function test_explore_shows_sharer_name(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'Some Link', 'is_public' => true]);

        $this->get(route('explore'))->assertSee($user->name);
    }

    public function test_guest_sees_save_link_button(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['is_public' => true]);

        $this->get(route('explore'))->assertSee('bookmark_border');
    }

    public function test_logged_in_user_sees_save_to_collection_button(): void
    {
        $owner = $this->makeUser();
        $this->makeBookmark($owner, ['is_public' => true]);

        $viewer = $this->makeUser();
        $this->actingAs($viewer)->get(route('explore'))->assertSee('bookmark_add');
    }

    public function test_user_sees_their_own_bookmark_label_on_explore(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'My Own Link', 'is_public' => true]);

        $this->actingAs($user)
            ->get(route('explore'))
            ->assertSee('Your link');
    }

    public function test_explore_search_filters_by_title(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'Laravel Guide', 'is_public' => true]);
        $this->makeBookmark($user, ['title' => 'Vue Tutorial', 'is_public' => true]);

        $this->get(route('explore', ['search' => 'Laravel']))
            ->assertSee('Laravel Guide')
            ->assertDontSee('Vue Tutorial');
    }

    public function test_explore_category_filter_works(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'Dev Link', 'category' => 'Development', 'is_public' => true]);
        $this->makeBookmark($user, ['title' => 'Fun Link', 'category' => 'Entertainment', 'is_public' => true]);

        $this->get(route('explore', ['category' => 'Development']))
            ->assertSee('Dev Link')
            ->assertDontSee('Fun Link');
    }

    // ─── Save to collection ──────────────────────────────────────────────────

    public function test_user_can_save_public_bookmark_to_collection(): void
    {
        $owner = $this->makeUser();
        $bookmark = $this->makeBookmark($owner, [
            'title' => 'Shared Link',
            'url' => 'https://shared.com',
            'is_public' => true,
        ]);

        $saver = $this->makeUser();
        $this->actingAs($saver)
            ->post(route('explore.save', $bookmark))
            ->assertRedirect(route('bookmarks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $saver->id,
            'title' => 'Shared Link',
            'url' => 'https://shared.com',
            'is_public' => false, // saved copy is private by default
        ]);
    }

    public function test_user_cannot_save_private_bookmark(): void
    {
        $owner = $this->makeUser();
        $bookmark = $this->makeBookmark($owner, ['is_public' => false]);

        $this->actingAs($this->makeUser())
            ->post(route('explore.save', $bookmark))
            ->assertStatus(403);
    }

    public function test_saving_a_bookmark_does_not_affect_the_original(): void
    {
        $owner = $this->makeUser();
        $bookmark = $this->makeBookmark($owner, ['title' => 'Original', 'is_public' => true]);

        $saver = $this->makeUser();
        $this->actingAs($saver)->post(route('explore.save', $bookmark));

        // original still exists
        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id, 'user_id' => $owner->id]);
        // copy created for saver
        $this->assertDatabaseHas('bookmarks', ['title' => 'Original', 'user_id' => $saver->id]);
    }

    // ─── Authentication ───────────────────────────────────────────────────────

    public function test_login_page_loads(): void
    {
        $this->get(route('login'))->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $this->get(route('register'))->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('bookmarks.index'));

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = $this->makeUser();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('bookmarks.index'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $this->post(route('login'), ['email' => 'wrong@example.com', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
    }

    // ─── Route protection ────────────────────────────────────────────────────

    public function test_unauthenticated_users_redirected_from_dashboard(): void
    {
        $this->get(route('bookmarks.index'))->assertRedirect(route('login'));
    }

    public function test_unauthenticated_users_cannot_create_bookmarks(): void
    {
        $this->get(route('bookmarks.create'))->assertRedirect(route('login'));
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────

    public function test_dashboard_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->makeUser())
            ->get(route('bookmarks.index'))
            ->assertStatus(200);
    }

    public function test_dashboard_shows_empty_state(): void
    {
        $this->actingAs($this->makeUser())
            ->get(route('bookmarks.index'))
            ->assertSee('Your vault is empty.');
    }

    public function test_dashboard_shows_bookmark_count(): void
    {
        $user = $this->makeUser();
        Bookmark::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('bookmarks.index'))
            ->assertSee('3');
    }

    public function test_user_only_sees_own_bookmarks(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();
        $this->makeBookmark($userA, ['title' => 'User A Link']);
        $this->makeBookmark($userB, ['title' => 'User B Link']);

        $this->actingAs($userA)
            ->get(route('bookmarks.index'))
            ->assertSee('User A Link')
            ->assertDontSee('User B Link');
    }

    // ─── Create ──────────────────────────────────────────────────────────────

    public function test_create_page_loads(): void
    {
        $this->actingAs($this->makeUser())
            ->get(route('bookmarks.create'))
            ->assertStatus(200);
    }

    public function test_valid_bookmark_can_be_created(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post(route('bookmarks.store'), [
            'title' => 'Laravel Docs',
            'url' => 'https://laravel.com/docs',
            'category' => 'Development',
            'is_public' => '0',
        ])->assertRedirect(route('bookmarks.index'));

        $this->assertDatabaseHas('bookmarks', ['title' => 'Laravel Docs', 'user_id' => $user->id]);
    }

    public function test_bookmark_title_is_required(): void
    {
        $this->actingAs($this->makeUser())
            ->post(route('bookmarks.store'), ['title' => '', 'url' => 'https://example.com'])
            ->assertSessionHasErrors('title');
    }

    public function test_bookmark_url_must_be_valid(): void
    {
        $this->actingAs($this->makeUser())
            ->post(route('bookmarks.store'), ['title' => 'Test', 'url' => 'not-a-url'])
            ->assertSessionHasErrors('url');
    }

    public function test_bookmark_title_max_100_characters(): void
    {
        $this->actingAs($this->makeUser())
            ->post(route('bookmarks.store'), ['title' => str_repeat('a', 101), 'url' => 'https://example.com'])
            ->assertSessionHasErrors('title');
    }

    public function test_bookmark_can_be_created_as_public(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post(route('bookmarks.store'), [
            'title' => 'Public Bookmark',
            'url' => 'https://example.com',
            'is_public' => '1',
        ]);

        $this->assertDatabaseHas('bookmarks', [
            'title' => 'Public Bookmark',
            'user_id' => $user->id,
            'is_public' => true,
        ]);
    }

    // ─── Edit / Update ───────────────────────────────────────────────────────

    public function test_edit_page_loads_with_prefilled_values(): void
    {
        $user = $this->makeUser();
        $bookmark = $this->makeBookmark($user, ['title' => 'Old Title']);

        $this->actingAs($user)
            ->get(route('bookmarks.edit', $bookmark))
            ->assertStatus(200)
            ->assertSee('Old Title');
    }

    public function test_user_cannot_edit_another_users_bookmark(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();
        $bookmark = $this->makeBookmark($userA);

        $this->actingAs($userB)
            ->get(route('bookmarks.edit', $bookmark))
            ->assertStatus(403);
    }

    public function test_bookmark_can_be_updated(): void
    {
        $user = $this->makeUser();
        $bookmark = $this->makeBookmark($user, ['title' => 'Old Title']);

        $this->actingAs($user)->put(route('bookmarks.update', $bookmark), [
            'title' => 'New Title',
            'url' => 'https://updated.com',
            'is_public' => '0',
        ])->assertRedirect(route('bookmarks.index'));

        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id, 'title' => 'New Title']);
    }

    public function test_user_cannot_update_another_users_bookmark(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();
        $bookmark = $this->makeBookmark($userA, ['title' => 'Original']);

        $this->actingAs($userB)->put(route('bookmarks.update', $bookmark), [
            'title' => 'Hijacked',
            'url' => 'https://hacker.com',
        ])->assertStatus(403);

        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id, 'title' => 'Original']);
    }

    // ─── Delete ──────────────────────────────────────────────────────────────

    public function test_bookmark_can_be_deleted(): void
    {
        $user = $this->makeUser();
        $bookmark = $this->makeBookmark($user);

        $this->actingAs($user)
            ->delete(route('bookmarks.destroy', $bookmark))
            ->assertRedirect(route('bookmarks.index'));

        $this->assertDatabaseMissing('bookmarks', ['id' => $bookmark->id]);
    }

    public function test_user_cannot_delete_another_users_bookmark(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();
        $bookmark = $this->makeBookmark($userA);

        $this->actingAs($userB)
            ->delete(route('bookmarks.destroy', $bookmark))
            ->assertStatus(403);

        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id]);
    }

    // ─── Favorites ───────────────────────────────────────────────────────────

    public function test_user_can_toggle_favorite(): void
    {
        $user = $this->makeUser();
        $bookmark = $this->makeBookmark($user, ['is_favorite' => false]);

        $this->actingAs($user)
            ->post(route('bookmarks.favorite', $bookmark))
            ->assertRedirect();

        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id, 'is_favorite' => true]);
    }

    public function test_user_cannot_favorite_another_users_bookmark(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();
        $bookmark = $this->makeBookmark($userA);

        $this->actingAs($userB)
            ->post(route('bookmarks.favorite', $bookmark))
            ->assertStatus(403);
    }

    // ─── Public toggle ───────────────────────────────────────────────────────

    public function test_user_can_toggle_public(): void
    {
        $user = $this->makeUser();
        $bookmark = $this->makeBookmark($user, ['is_public' => false]);

        $this->actingAs($user)
            ->post(route('bookmarks.public', $bookmark))
            ->assertRedirect();

        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id, 'is_public' => true]);
    }

    public function test_user_cannot_toggle_public_on_another_users_bookmark(): void
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();
        $bookmark = $this->makeBookmark($userA, ['is_public' => false]);

        $this->actingAs($userB)
            ->post(route('bookmarks.public', $bookmark))
            ->assertStatus(403);
    }

    // ─── Search & filter ─────────────────────────────────────────────────────

    public function test_search_returns_matching_bookmarks(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'Laravel Tutorial']);
        $this->makeBookmark($user, ['title' => 'Vue Guide']);

        $this->actingAs($user)
            ->get(route('bookmarks.index', ['search' => 'Laravel']))
            ->assertSee('Laravel Tutorial')
            ->assertDontSee('Vue Guide');
    }

    public function test_category_filter_works(): void
    {
        $user = $this->makeUser();
        $this->makeBookmark($user, ['title' => 'Dev Link', 'category' => 'Development']);
        $this->makeBookmark($user, ['title' => 'Fun Link', 'category' => 'Entertainment']);

        $this->actingAs($user)
            ->get(route('bookmarks.index', ['category' => 'Development']))
            ->assertSee('Dev Link')
            ->assertDontSee('Fun Link');
    }

    // ─── Misc ────────────────────────────────────────────────────────────────

    public function test_domain_accessor_returns_hostname(): void
    {
        $user = $this->makeUser();
        $bookmark = $this->makeBookmark($user, ['url' => 'https://laravel.com/docs/routing']);

        $this->assertEquals('laravel.com', $bookmark->domain);
    }

    public function test_all_categories_present_on_create_form(): void
    {
        $response = $this->actingAs($this->makeUser())->get(route('bookmarks.create'));

        foreach (Bookmark::CATEGORIES as $category) {
            $response->assertSee($category);
        }
    }
}

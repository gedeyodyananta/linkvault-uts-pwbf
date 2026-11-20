<?php

namespace Database\Factories;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bookmark>
 */
class BookmarkFactory extends Factory
{
    /**
     * @var array<string, list<array{title: string, url: string, description: string}>>
     */
    private static array $pool = [
        'Development' => [
            ['title' => 'Laravel Documentation', 'url' => 'https://laravel.com/docs', 'description' => 'Official Laravel framework documentation.'],
            ['title' => 'PHP: The Right Way', 'url' => 'https://phptherightway.com', 'description' => 'Best practices and coding standards for PHP developers.'],
            ['title' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org', 'description' => 'Comprehensive reference for HTML, CSS, and JavaScript.'],
            ['title' => 'GitHub', 'url' => 'https://github.com', 'description' => 'Host and review code, manage projects, and build software.'],
            ['title' => 'Stack Overflow', 'url' => 'https://stackoverflow.com', 'description' => 'Q&A community for programmers.'],
            ['title' => 'Composer', 'url' => 'https://getcomposer.org', 'description' => 'Dependency manager for PHP.'],
        ],
        'Education' => [
            ['title' => 'freeCodeCamp', 'url' => 'https://www.freecodecamp.org', 'description' => 'Free coding tutorials and certifications.'],
            ['title' => 'Coursera', 'url' => 'https://www.coursera.org', 'description' => 'Online courses from top universities and companies.'],
            ['title' => 'Khan Academy', 'url' => 'https://www.khanacademy.org', 'description' => 'Free online learning for math, science, and more.'],
            ['title' => 'Laracasts', 'url' => 'https://laracasts.com', 'description' => 'Laravel and PHP video tutorials.'],
        ],
        'Design' => [
            ['title' => 'Dribbble', 'url' => 'https://dribbble.com', 'description' => 'Design inspiration from creative professionals.'],
            ['title' => 'Figma', 'url' => 'https://www.figma.com', 'description' => 'Collaborative UI design tool.'],
            ['title' => 'Unsplash', 'url' => 'https://unsplash.com', 'description' => 'Free high-resolution photos.'],
            ['title' => 'Coolors', 'url' => 'https://coolors.co', 'description' => 'Fast color palette generator.'],
        ],
        'Productivity' => [
            ['title' => 'Notion', 'url' => 'https://www.notion.so', 'description' => 'All-in-one workspace for notes, tasks, and wikis.'],
            ['title' => 'Trello', 'url' => 'https://trello.com', 'description' => 'Visual project management with boards and cards.'],
            ['title' => 'Todoist', 'url' => 'https://todoist.com', 'description' => 'Task manager to organize work and personal projects.'],
        ],
        'Entertainment' => [
            ['title' => 'YouTube', 'url' => 'https://www.youtube.com', 'description' => 'Video streaming platform.'],
            ['title' => 'Spotify', 'url' => 'https://www.spotify.com', 'description' => 'Music and podcast streaming service.'],
            ['title' => 'Reddit', 'url' => 'https://www.reddit.com', 'description' => 'Community discussions and content sharing.'],
        ],
        'Other' => [
            ['title' => 'Wikipedia', 'url' => 'https://www.wikipedia.org', 'description' => 'Free online encyclopedia.'],
            ['title' => 'Hacker News', 'url' => 'https://news.ycombinator.com', 'description' => 'Tech news and startup discussions.'],
        ],
    ];

    /**
     * @return array{title: string, url: string, description: string|null, category: string, is_favorite: bool, is_public: bool}
     */
    public function definition(): array
    {
        $category = fake()->randomElement(array_keys(self::$pool));
        $item = fake()->randomElement(self::$pool[$category]);

        return [
            'title' => $item['title'],
            'url' => $item['url'],
            'description' => fake()->boolean(70) ? $item['description'] : null,
            'category' => $category,
            'is_favorite' => fake()->boolean(20),
            'is_public' => fake()->boolean(40),
        ];
    }
}

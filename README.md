**Language:** English | [Bahasa Indonesia](README.id.md)

# LinkVault

LinkVault is a Laravel-based web application for managing bookmarks, allowing users to save, organize, and share their favorite links. Each user has a private "vault" filled with bookmarks that can include a category, description, favorite status, and visibility setting (public/private). Public bookmarks can be explored by all users through the Explore page and saved into their own collection.

## Academic Context

This project is the Midterm Exam (UTS) for the course Framework-Based Web Programming (PWBF).

## Main Features

- User Authentication: registration, login, and logout with sessions.
- Personal Bookmark Dashboard: lists all of the user's bookmarks with search by title and filter by category.
- Bookmark Management (CRUD): create, update, and delete bookmarks (title, URL, description, category).
- Favorite Status: mark bookmarks as favorites and view favorite bookmarks.
- Public/Private Visibility: control whether a bookmark is visible to everyone or kept private.
- Explore Page: browse public bookmarks from all users, with search and category filters.
- Save to Collection: copy another user's public bookmark into your own collection.
- User Profile: profile page displaying a user's public bookmarks.
- Statistics: total, favorites, private, and public bookmark counts on the dashboard.
- Fixed Categories: Development, Education, Design, Productivity, Entertainment, Other.

## Tech Stack

- PHP 8.3+ (tested on 8.5)
- Laravel 13.x
- Database: SQLite (default), supports MySQL/PostgreSQL
- Frontend: Blade Templates, Tailwind CSS 4, Vite
- ORM: Eloquent

## Installation & Running Locally

```bash
git clone https://github.com/gedeyodyananta/linkvault-uts-pwbf.git
cd linkvault-uts-pwbf

composer install
cp .env.example .env
php artisan key:generate

# Create the SQLite database (when using the default)
touch database/database.sqlite
php artisan migrate

npm install
npm run build

php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

Alternatively, run `composer run setup` to automatically install dependencies, run migrations, and build the frontend.

## Student Identity

- Name: Gede Yodyananta
- NIM: 25354105
- Class: 3A

## License

This project is created for academic purposes (UTS PWBF).
"
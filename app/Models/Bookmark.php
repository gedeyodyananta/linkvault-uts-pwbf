<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    use HasFactory;

    /** Predefined category list used across create/edit forms and filters. */
    public const CATEGORIES = [
        'Development',
        'Education',
        'Design',
        'Productivity',
        'Entertainment',
        'Other',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'description',
        'category',
        'is_favorite',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_favorite' => 'boolean',
            'is_public' => 'boolean',
        ];
    }

    /**
     * Return the hostname portion of the URL for display purposes.
     */
    public function getDomainAttribute(): string
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        return $host ?? $this->url;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

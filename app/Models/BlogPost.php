<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'title',
    'slug',
    'body',
    'featured_image_path',
    'is_published',
    'publish_for',
    'published_at',
])]
class BlogPost extends Model
{
    public const PUBLISH_FOR_HOST = 'host';

    public const PUBLISH_FOR_USER = 'user';

    public const PUBLISH_FOR_BOTH = 'both';

    /**
     * @return array<string, string>
     */
    public static function publishForOptions(): array
    {
        return [
            self::PUBLISH_FOR_BOTH => __('Both pages (user & host)'),
            self::PUBLISH_FOR_USER => __('User community page only'),
            self::PUBLISH_FOR_HOST => __('Host community page only'),
        ];
    }

    public function publishForLabel(): string
    {
        return self::publishForOptions()[$this->publish_for] ?? (string) $this->publish_for;
    }

    public function excerpt(int $length = 180): string
    {
        $text = trim(strip_tags(html_entity_decode((string) $this->body)));

        return Str::limit($text, $length);
    }

    /**
     * @param  Builder<BlogPost>  $query
     * @return Builder<BlogPost>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<BlogPost>  $query
     * @return Builder<BlogPost>
     */
    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        if ($audience === self::PUBLISH_FOR_USER) {
            return $query->whereIn('publish_for', [self::PUBLISH_FOR_USER, self::PUBLISH_FOR_BOTH]);
        }

        if ($audience === self::PUBLISH_FOR_HOST) {
            return $query->whereIn('publish_for', [self::PUBLISH_FOR_HOST, self::PUBLISH_FOR_BOTH]);
        }

        return $query->whereRaw('1 = 0');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<BlogComment, $this>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogComment::class)->latest();
    }

    public function featuredImageUrl(): ?string
    {
        return GymListing::publicStorageUrl($this->featured_image_path);
    }

    public static function makeUniqueSlug(string $title, ?int $exceptId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'post';
        }

        $slug = $base;
        $i = 1;
        while (static::query()
            ->where('slug', $slug)
            ->when($exceptId !== null, fn (Builder $q) => $q->where('id', '!=', $exceptId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function deleteStoredFeaturedImage(): void
    {
        if (! is_string($this->featured_image_path) || $this->featured_image_path === '') {
            return;
        }

        \Illuminate\Support\Facades\Storage::disk('public')->delete($this->featured_image_path);
    }

    protected static function booted(): void
    {
        static::deleting(function (BlogPost $post): void {
            $post->deleteStoredFeaturedImage();
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'blog_post_id',
    'user_id',
    'body',
])]
class BlogComment extends Model
{
    public function blogPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

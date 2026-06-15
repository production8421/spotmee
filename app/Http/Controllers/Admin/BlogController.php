<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Services\Blog\BlogFeaturedImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogPost::class, 'blog_post');
    }

    public function index(): View
    {
        $posts = BlogPost::query()
            ->with('user:id,name')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.blog.index', [
            'posts' => $posts,
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isPublished = $request->boolean('is_published');

        $post = BlogPost::query()->create([
            'user_id' => $request->user()?->id,
            'title' => $validated['title'],
            'slug' => BlogPost::makeUniqueSlug($validated['title']),
            'body' => $validated['body'],
            'is_published' => $isPublished,
            'publish_for' => $validated['publish_for'],
            'published_at' => $isPublished ? now() : null,
        ]);

        if ($request->hasFile('featured_image')) {
            $path = app(BlogFeaturedImageStorage::class)->store(
                $request->file('featured_image'),
                $post->id,
            );
            $post->update(['featured_image_path' => $path]);
        }

        return redirect()
            ->route('admin.blog.index')
            ->with('status', __('Blog post created.'));
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog.edit', [
            'post' => $blogPost,
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        $validated = $request->validated();
        $isPublished = $request->boolean('is_published');
        $wasPublished = (bool) $blogPost->is_published;

        $blogPost->title = $validated['title'];
        $blogPost->slug = BlogPost::makeUniqueSlug($validated['title'], $blogPost->id);
        $blogPost->body = $validated['body'];
        $blogPost->is_published = $isPublished;
        $blogPost->publish_for = $validated['publish_for'];

        if ($isPublished && ! $wasPublished) {
            $blogPost->published_at = now();
        } elseif (! $isPublished) {
            $blogPost->published_at = null;
        }

        if ($request->boolean('remove_featured_image')) {
            $blogPost->deleteStoredFeaturedImage();
            $blogPost->featured_image_path = null;
        }

        if ($request->hasFile('featured_image')) {
            $blogPost->deleteStoredFeaturedImage();
            $blogPost->featured_image_path = app(BlogFeaturedImageStorage::class)->store(
                $request->file('featured_image'),
                $blogPost->id,
            );
        }

        $blogPost->save();

        return redirect()
            ->route('admin.blog.index')
            ->with('status', __('Blog post updated.'));
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();

        return redirect()
            ->route('admin.blog.index')
            ->with('status', __('Blog post deleted.'));
    }
}

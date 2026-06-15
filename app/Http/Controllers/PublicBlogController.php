<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Web\StoreBlogCommentRequest;
use App\Http\Requests\Web\StoreCommunityBlogPostRequest;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Services\Blog\BlogFeaturedImageStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PublicBlogController extends Controller
{
    public function userIndex(): View
    {
        return $this->index(
            BlogPost::PUBLISH_FOR_USER,
            __('User Blog'),
            __('Training tips, platform updates, and stories for guests and subscribers.'),
            'community.user',
            'community.user.show',
        );
    }

    public function hostIndex(): View|RedirectResponse
    {
        if ($redirect = $this->redirectUnlessCanViewHostBlog()) {
            return $redirect;
        }

        return $this->index(
            BlogPost::PUBLISH_FOR_HOST,
            __('Host Blog'),
            __('Resources, hosting tips, and community news for SPOTMEE hosts.'),
            'community.host',
            'community.host.show',
        );
    }

    public function userCreate(): View
    {
        $this->assertCanContribute(BlogPost::PUBLISH_FOR_USER);

        return $this->createForm(BlogPost::PUBLISH_FOR_USER, 'community.user', 'community.user.store');
    }

    public function hostCreate(): View
    {
        $this->assertCanContribute(BlogPost::PUBLISH_FOR_HOST);

        return $this->createForm(BlogPost::PUBLISH_FOR_HOST, 'community.host', 'community.host.store');
    }

    public function userStore(StoreCommunityBlogPostRequest $request): RedirectResponse
    {
        return $this->storePost($request, BlogPost::PUBLISH_FOR_USER, 'community.user.show');
    }

    public function hostStore(StoreCommunityBlogPostRequest $request): RedirectResponse
    {
        return $this->storePost($request, BlogPost::PUBLISH_FOR_HOST, 'community.host.show');
    }

    public function userStoreComment(StoreBlogCommentRequest $request, string $slug): RedirectResponse
    {
        return $this->storeComment($request, BlogPost::PUBLISH_FOR_USER, $slug, 'community.user.show');
    }

    public function hostStoreComment(StoreBlogCommentRequest $request, string $slug): RedirectResponse
    {
        return $this->storeComment($request, BlogPost::PUBLISH_FOR_HOST, $slug, 'community.host.show');
    }

    public function userShow(string $slug): View
    {
        return $this->show(
            BlogPost::PUBLISH_FOR_USER,
            $slug,
            'community.user',
            'community.user.show',
            'community.user.comments.store',
        );
    }

    public function hostShow(string $slug): View|RedirectResponse
    {
        if ($redirect = $this->redirectUnlessCanViewHostBlog()) {
            return $redirect;
        }

        return $this->show(
            BlogPost::PUBLISH_FOR_HOST,
            $slug,
            'community.host',
            'community.host.show',
            'community.host.comments.store',
        );
    }

    private function index(
        string $audience,
        string $heroTitle,
        string $heroSubtitle,
        string $indexRoute,
        string $showRoute,
    ): View {
        if (! Schema::hasTable((new BlogPost)->getTable())) {
            $page = max(1, (int) request()->query('page', 1));

            return view('web.community.index', [
                'posts' => new LengthAwarePaginator([], 0, 12, $page, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]),
                'audience' => $audience,
                'heroTitle' => $heroTitle,
                'heroSubtitle' => $heroSubtitle,
                'indexRoute' => $indexRoute,
                'showRoute' => $showRoute,
                'canContribute' => $this->canContribute($audience),
                'createRoute' => $this->createRouteForAudience($audience),
            ]);
        }

        $posts = BlogPost::query()
            ->published()
            ->forAudience($audience)
            ->with('user:id,name')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('web.community.index', [
            'posts' => $posts,
            'audience' => $audience,
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'indexRoute' => $indexRoute,
            'showRoute' => $showRoute,
            'canContribute' => $this->canContribute($audience),
            'createRoute' => $this->createRouteForAudience($audience),
        ]);
    }

    private function show(
        string $audience,
        string $slug,
        string $indexRoute,
        string $showRoute,
        string $commentStoreRoute,
    ): View {
        $postQuery = BlogPost::query()
            ->published()
            ->forAudience($audience)
            ->with('user:id,name')
            ->where('slug', $slug);

        if (Schema::hasTable((new BlogComment)->getTable())) {
            $postQuery->with(['comments' => fn ($q) => $q->with('user:id,name')]);
        }

        $post = $postQuery->firstOrFail();

        if (! Schema::hasTable((new BlogComment)->getTable())) {
            $post->setRelation('comments', collect());
        }

        return view('web.community.show', [
            'post' => $post,
            'audience' => $audience,
            'indexRoute' => $indexRoute,
            'showRoute' => $showRoute,
            'commentStoreRoute' => $commentStoreRoute,
            'canComment' => Schema::hasTable((new BlogComment)->getTable()) && $this->canComment($audience),
            'listLabel' => $audience === BlogPost::PUBLISH_FOR_HOST
                ? __('Host Blog')
                : __('User Blog'),
        ]);
    }

    private function createForm(string $audience, string $indexRoute, string $storeRoute): View
    {
        return view('web.community.create', [
            'audience' => $audience,
            'indexRoute' => $indexRoute,
            'storeRoute' => $storeRoute,
            'blogLabel' => $audience === BlogPost::PUBLISH_FOR_HOST
                ? __('Host Blog')
                : __('User Blog'),
        ]);
    }

    private function storePost(
        StoreCommunityBlogPostRequest $request,
        string $audience,
        string $showRoute,
    ): RedirectResponse {
        $this->assertCanContribute($audience);

        $validated = $request->validated();
        $isPublished = $request->boolean('is_published', true);

        $post = BlogPost::query()->create([
            'user_id' => $request->user()?->id,
            'title' => $validated['title'],
            'slug' => BlogPost::makeUniqueSlug($validated['title']),
            'body' => $validated['body'],
            'is_published' => $isPublished,
            'publish_for' => $audience,
            'published_at' => $isPublished ? now() : null,
        ]);

        if ($request->hasFile('featured_image')) {
            $path = app(BlogFeaturedImageStorage::class)->store(
                $request->file('featured_image'),
                $post->id,
            );
            $post->update(['featured_image_path' => $path]);
        }

        if (! $isPublished) {
            return redirect()
                ->route($this->indexRouteForAudience($audience))
                ->with('status', __('Your article was saved as a draft.'));
        }

        return redirect()
            ->route($showRoute, ['slug' => $post->slug])
            ->with('status', __('Your article was published.'));
    }

    private function storeComment(
        StoreBlogCommentRequest $request,
        string $audience,
        string $slug,
        string $showRoute,
    ): RedirectResponse {
        $this->assertCanComment($audience);

        $validated = $request->validated();

        $post = BlogPost::query()
            ->published()
            ->forAudience($audience)
            ->where('slug', $slug)
            ->firstOrFail();

        if (! Schema::hasTable((new BlogComment)->getTable())) {
            return redirect()
                ->route($showRoute, ['slug' => $post->slug])
                ->withFragment('comments')
                ->with('status', __('Comments are temporarily unavailable. Please try again later.'));
        }

        BlogComment::query()->create([
            'blog_post_id' => $post->id,
            'user_id' => $request->user()?->id,
            'body' => $validated['body'],
        ]);

        return redirect()
            ->route($showRoute, ['slug' => $post->slug])
            ->withFragment('comments')
            ->with('status', __('Your comment was posted.'));
    }

    private function canComment(string $audience): bool
    {
        $user = auth()->user();
        if ($user === null) {
            return false;
        }

        if ($user->hasRole(UserRole::Administrator->value)) {
            return true;
        }

        if ($audience === BlogPost::PUBLISH_FOR_HOST) {
            return $user->hasRole(UserRole::Host->value);
        }

        return true;
    }

    private function canContribute(string $audience): bool
    {
        $user = auth()->user();
        if ($user === null) {
            return false;
        }

        if ($audience === BlogPost::PUBLISH_FOR_HOST) {
            return $user->hasRole(UserRole::Host->value);
        }

        return true;
    }

    private function assertCanComment(string $audience): void
    {
        if (! $this->canComment($audience)) {
            throw new AccessDeniedHttpException;
        }
    }

    private function assertCanContribute(string $audience): void
    {
        if (! $this->canContribute($audience)) {
            throw new AccessDeniedHttpException;
        }
    }

    private function createRouteForAudience(string $audience): string
    {
        return $audience === BlogPost::PUBLISH_FOR_HOST
            ? 'community.host.create'
            : 'community.user.create';
    }

    private function indexRouteForAudience(string $audience): string
    {
        return $audience === BlogPost::PUBLISH_FOR_HOST
            ? 'community.host'
            : 'community.user';
    }

    private function redirectUnlessCanViewHostBlog(): ?RedirectResponse
    {
        if (auth()->user()?->canViewHostBlog()) {
            return null;
        }

        return redirect()
            ->route('become-a-host')
            ->with('status', __('The host blog is for SPOTMEE hosts only. Register and apply to become a host to access it.'));
    }
}

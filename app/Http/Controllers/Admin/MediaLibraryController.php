<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaAssetRequest;
use App\Models\MediaAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaLibraryController extends Controller
{
    public function pickerImages(): JsonResponse
    {
        $items = MediaAsset::query()
            ->where('mime_type', 'like', 'image/%')
            ->where('mime_type', 'not like', 'image/svg%')
            ->latest()
            ->limit(120)
            ->get(['id', 'original_name', 'mime_type']);

        return response()->json([
            'data' => $items->map(static fn (MediaAsset $m): array => [
                'id' => $m->id,
                'name' => $m->original_name,
                'kind' => 'image',
                'preview_url' => route('admin.media.stream', $m),
            ]),
        ]);
    }

    public function pickerHeroAssets(): JsonResponse
    {
        $items = MediaAsset::query()
            ->where(function ($q): void {
                $q->where(function ($q2): void {
                    $q2->where('mime_type', 'like', 'image/%')
                        ->where('mime_type', 'not like', 'image/svg%');
                })->orWhereIn('mime_type', ['video/mp4', 'video/webm', 'video/quicktime']);
            })
            ->latest()
            ->limit(120)
            ->get(['id', 'original_name', 'mime_type']);

        return response()->json([
            'data' => $items->map(static function (MediaAsset $m): array {
                $kind = $m->isVideo() ? 'video' : 'image';

                return [
                    'id' => $m->id,
                    'name' => $m->original_name,
                    'kind' => $kind,
                    'preview_url' => route('admin.media.stream', $m),
                ];
            }),
        ]);
    }

    public function index(): View
    {
        $media = MediaAsset::query()
            ->with('user:id,name')
            ->latest()
            ->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(StoreMediaAssetRequest $request): RedirectResponse
    {
        $disk = 'local';
        /** @var array<int, UploadedFile> $uploadedFiles */
        $uploadedFiles = $request->file('files', []);
        $saved = 0;

        foreach ($uploadedFiles as $uploaded) {
            $path = $uploaded->store('media', $disk);

            if ($path === false) {
                return redirect()
                    ->route('admin.media.index')
                    ->withErrors(['files' => __('Could not store :name. Try again.', ['name' => $uploaded->getClientOriginalName()])]);
            }

            MediaAsset::query()->create([
                'user_id' => $request->user()->id,
                'disk' => $disk,
                'path' => $path,
                'original_name' => $uploaded->getClientOriginalName(),
                'mime_type' => (string) ($uploaded->getMimeType() ?? 'application/octet-stream'),
                'size' => $uploaded->getSize(),
            ]);
            $saved++;
        }

        $status = $saved === 1
            ? __('One file uploaded.')
            : __(':count files uploaded.', ['count' => $saved]);

        return redirect()
            ->route('admin.media.index')
            ->with('status', $status);
    }

    public function stream(MediaAsset $media): StreamedResponse
    {
        $this->authorizeMedia($media);

        return $media->streamInlineResponse();
    }

    public function destroy(MediaAsset $media): RedirectResponse
    {
        $this->authorizeMedia($media);

        if ($media->pathIsAllowed()) {
            $media->deleteStoredFile();
        }

        $media->delete();

        return redirect()
            ->route('admin.media.index')
            ->with('status', __('Media deleted.'));
    }

    private function authorizeMedia(MediaAsset $media): void
    {
        if (! $media->pathIsAllowed()) {
            abort(404);
        }
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Support\MediaUploadLimits;
use Illuminate\Foundation\Http\FormRequest;

class StoreMediaAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Validation\Rules\File>>
     */
    public function rules(): array
    {
        $maxFiles = MediaUploadLimits::maxFilesPerRequest();

        // max in kilobytes (~50 MB) per file; batch size cannot exceed PHP max_file_uploads
        return [
            'files' => ['required', 'array', 'min:1', 'max:'.$maxFiles],
            'files.*' => [
                'file',
                'max:51200',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $maxFiles = MediaUploadLimits::maxFilesPerRequest();

        return [
            'files.required' => __('Please choose at least one file to upload.'),
            'files.min' => __('Please choose at least one file to upload.'),
            'files.max' => __('You may upload at most :max files in one request. If you need more, raise max_file_uploads in php.ini (PHP default is 20).', ['max' => $maxFiles]),
            'files.*.max' => __('Each file may not be greater than :max kilobytes.', ['max' => 51200]),
            'files.*.mimetypes' => __('Only images (JPEG, PNG, GIF, WebP) and videos (MP4, WebM, MOV) are allowed.'),
        ];
    }
}

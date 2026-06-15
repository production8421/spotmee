<?php

namespace App\Http\Requests\Concerns;

use App\Services\Blog\BlogFeaturedImageStorage;
use App\Services\Blog\BlogPostInputSanitizer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

trait ValidatesBlogPostInput
{
    protected function prepareForValidation(): void
    {
        $sanitizer = app(BlogPostInputSanitizer::class);

        if ($this->has('title')) {
            $this->merge([
                'title' => $sanitizer->sanitizeTitle((string) $this->input('title')),
            ]);
        }

        if ($this->has('body')) {
            $this->merge([
                'body' => $sanitizer->sanitizeBody((string) $this->input('body')),
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $file = $this->file('featured_image');
            if (! $file instanceof UploadedFile) {
                return;
            }

            try {
                app(BlogFeaturedImageStorage::class)->assertValidImage($file);
            } catch (InvalidArgumentException) {
                $validator->errors()->add(
                    'featured_image',
                    __('The featured image must be a valid JPG or PNG file no larger than 1 MB.')
                );
            }
        });
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function blogPostRules(bool $includePublishFor = false, bool $includeRemoveImage = false): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:200', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
            'body' => [
                'required',
                'string',
                'max:'.BlogPostInputSanitizer::MAX_BODY_LENGTH,
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (trim(strip_tags((string) $value)) === '') {
                        $fail(__('Body is required.'));
                    }
                },
            ],
            'featured_image' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,jpg,png',
                'mimetypes:image/jpeg,image/png',
                'max:1024',
            ],
            'is_published' => ['sometimes', 'boolean'],
        ];

        if ($includePublishFor) {
            $rules['publish_for'] = ['required', 'string', 'in:host,user,both'];
        }

        if ($includeRemoveImage) {
            $rules['remove_featured_image'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }
}

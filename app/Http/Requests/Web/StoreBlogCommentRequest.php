<?php

namespace App\Http\Requests\Web;

use App\Services\Blog\BlogPostInputSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('body')) {
            $this->merge([
                'body' => app(BlogPostInputSanitizer::class)->sanitizeComment((string) $this->input('body')),
            ]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2', 'max:5000', 'regex:/^[^\x00-\x08\x0B\x0C\x0E-\x1F<>]*$/u'],
        ];
    }
}

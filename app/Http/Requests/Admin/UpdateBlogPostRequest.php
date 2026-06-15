<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Http\Requests\Concerns\ValidatesBlogPostInput;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogPostRequest extends FormRequest
{
    use ValidatesBlogPostInput;

    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return $this->blogPostRules(includePublishFor: true, includeRemoveImage: true);
    }
}

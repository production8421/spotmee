<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Concerns\ValidatesBlogPostInput;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommunityBlogPostRequest extends FormRequest
{
    use ValidatesBlogPostInput;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return $this->blogPostRules();
    }
}

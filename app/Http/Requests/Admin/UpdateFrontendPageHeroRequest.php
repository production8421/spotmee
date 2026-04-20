<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateFrontendPageHeroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::Administrator->value) ?? false;
    }

    public function heroPrefix(): string
    {
        return match ($this->route()?->getName()) {
            'admin.frontend.find-a-gym.update' => 'find_a_gym',
            'admin.frontend.become-a-host.update' => 'become_a_host',
            'admin.frontend.faq.update' => 'faq',
            'admin.frontend.contact.update' => 'contact',
            'admin.frontend.waiver-of-liability-host.update' => 'waiver_liability_host',
            'admin.frontend.waiver-of-liability-user.update' => 'waiver_liability_user',
            'admin.frontend.cancellation-policy.update' => 'cancellation_policy',
            default => abort(404),
        };
    }

    public function heroRedirectRouteName(): string
    {
        return match ($this->heroPrefix()) {
            'find_a_gym' => 'admin.frontend.find-a-gym',
            'become_a_host' => 'admin.frontend.become-a-host',
            'faq' => 'admin.frontend.faq',
            'contact' => 'admin.frontend.contact',
            'waiver_liability_host' => 'admin.frontend.waiver-of-liability-host',
            'waiver_liability_user' => 'admin.frontend.waiver-of-liability-user',
            'cancellation_policy' => 'admin.frontend.cancellation-policy',
            default => abort(404),
        };
    }

    public function heroSavedStatusMessage(): string
    {
        return match ($this->heroPrefix()) {
            'find_a_gym' => __('Find a Gym page saved.'),
            'become_a_host' => __('Become a Host page saved.'),
            'faq' => __('FAQ page saved.'),
            'contact' => __('Contact page saved.'),
            'waiver_liability_host' => __('Waiver of Liability Host page saved.'),
            'waiver_liability_user' => __('Waiver of Liability User page saved.'),
            'cancellation_policy' => __('Cancellation Policy page saved.'),
            default => __('Page saved.'),
        };
    }

    protected function prepareForValidation(): void
    {
        $prefix = $this->heroPrefix();
        $c = trim((string) $this->input("{$prefix}_hero_background_color", ''));
        if ($c === '') {
            $this->merge(["{$prefix}_hero_background_color" => null]);
        }

        if ($this->route()?->getName() === 'admin.frontend.faq.update') {
            $items = collect($this->input('faq_items', []))
                ->filter(fn (mixed $row): bool => is_array($row))
                ->map(function (array $row): array {
                    return [
                        'question' => trim((string) ($row['question'] ?? '')),
                        'answer' => trim((string) ($row['answer'] ?? '')),
                    ];
                })
                ->values()
                ->all();
            $this->merge(['faq_items' => $items]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        if ($this->heroPrefix() !== 'faq') {
            return;
        }

        $validator->after(function (Validator $validator): void {
            foreach ($this->input('faq_items', []) as $i => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $q = trim((string) ($row['question'] ?? ''));
                $a = trim((string) ($row['answer'] ?? ''));
                if ($q !== '' && $a === '') {
                    $validator->errors()->add("faq_items.{$i}.answer", __('Enter an answer, or clear the question.'));
                }
                if ($a !== '' && $q === '') {
                    $validator->errors()->add("faq_items.{$i}.question", __('Enter a question, or clear the answer.'));
                }
            }
        });
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        $prefix = $this->heroPrefix();

        $rules = [
            "{$prefix}_hero_title" => ['nullable', 'string', 'max:200'],
            "{$prefix}_hero_background_color" => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];

        if ($prefix === 'faq') {
            $rules['faq_items'] = ['nullable', 'array', 'max:100'];
            $rules['faq_items.*.question'] = ['nullable', 'string', 'max:500'];
            $rules['faq_items.*.answer'] = ['nullable', 'string', 'max:10000'];
        }

        return $rules;
    }
}

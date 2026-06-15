@php
    $current = \Illuminate\Support\Facades\Route::currentRouteName();
    $isActive = fn (string ...$routes) => in_array($current, $routes, true)
        || collect($routes)->contains(fn ($r) => str_starts_with((string) $current, $r . '.'));

    $items = [
        [
            'label' => __('All posts'),
            'href' => route('community.user'),
            'active' => $isActive('community.user', 'community.user.show'),
            'icon' => 'fa-newspaper',
        ],
    ];

    if (auth()->check()) {
        $items[] = [
            'label' => __('Write article'),
            'href' => route('community.user.create'),
            'active' => $isActive('community.user.create'),
            'icon' => 'fa-pen',
        ];
    }

    $items = array_merge($items, [
        [
            'label' => __('Find a Gym'),
            'href' => route('find-a-gym'),
            'active' => $isActive('find-a-gym'),
            'icon' => 'fa-dumbbell',
        ],
        [
            'label' => __('How It Works'),
            'href' => route('how-it-works'),
            'active' => $isActive('how-it-works'),
            'icon' => 'fa-circle-info',
        ],
        [
            'label' => __('FAQ'),
            'href' => route('faq'),
            'active' => $isActive('faq'),
            'icon' => 'fa-circle-question',
        ],
        [
            'label' => __('Contact'),
            'href' => route('contact'),
            'active' => $isActive('contact'),
            'icon' => 'fa-envelope',
        ],
    ]);
@endphp

<nav class="blog-subnav" aria-label="{{ __('User blog menu') }}">
    <div class="site-container">
        <ul class="blog-subnav__list">
            @foreach ($items as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       @class(['blog-subnav__link', 'is-active' => $item['active']])>
                        <i class="fa-solid {{ $item['icon'] }} text-[12px]"></i>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>

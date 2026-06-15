@php
    $current = \Illuminate\Support\Facades\Route::currentRouteName();
    $isActive = fn (string ...$routes) => in_array($current, $routes, true)
        || collect($routes)->contains(fn ($r) => str_starts_with((string) $current, $r . '.'));

    $items = [
        [
            'label' => __('All posts'),
            'href' => route('community.host'),
            'active' => $isActive('community.host', 'community.host.show'),
            'icon' => 'fa-newspaper',
        ],
    ];

    if (auth()->check() && auth()->user()->hasRole(\App\Enums\UserRole::Host->value)) {
        $items[] = [
            'label' => __('Write article'),
            'href' => route('community.host.create'),
            'active' => $isActive('community.host.create'),
            'icon' => 'fa-pen',
        ];
    }

    $items = array_merge($items, [
        [
            'label' => __('Become a Host'),
            'href' => route('become-a-host'),
            'active' => $isActive('become-a-host', 'host.apply'),
            'icon' => 'fa-house-chimney-user',
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

    if (auth()->check() && auth()->user()->hasRole(\App\Enums\UserRole::Host->value)) {
        array_splice($items, 3, 0, [[
            'label' => __('My listings'),
            'href' => route('host.gym-listings.index'),
            'active' => str_starts_with((string) $current, 'host.gym-listings'),
            'icon' => 'fa-layer-group',
        ]]);
    }
@endphp

<nav class="blog-subnav blog-subnav--host" aria-label="{{ __('Host blog menu') }}">
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

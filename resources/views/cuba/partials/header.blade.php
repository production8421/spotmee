<!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
            <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="{{ route('dashboard') }}">@include('cuba.partials.brand-header-images')</a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
          </div>
          <div class="nav-right col p-0 ms-auto d-flex justify-content-end align-items-center">
            <ul class="nav-menus">
              <li class="px-2 d-none d-sm-flex align-items-center">
                <a
                  href="{{ route('home') }}"
                  class="btn btn-outline-primary btn-sm py-1"
                  target="_blank"
                  rel="noopener noreferrer"
                >{{ __('View site') }}</a>
              </li>
              <li class="language-nav">
                <div class="translate_wrapper">
                  <div class="current_lang">
                    <div class="lang"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">EN</span></div>
                  </div>
                </div>
              </li>
              <li class="fullscreen-body">                       <span>
                  <svg id="maximize-screen">
                    <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#full-screen"></use>
                  </svg></span></li>
              <li>
                <div class="mode">
                  <svg>
                    <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#moon"></use>
                  </svg>
                </div>
              </li>
              @php
                  $headerNotifications = \App\Services\HeaderNotifications::visibleFor(auth()->user());
                  $unreadNotificationCount = \App\Services\HeaderNotifications::unreadVisibleCount(auth()->user());
              @endphp
              <li class="onhover-dropdown">
                <div class="notification-box">
                  <svg>
                    <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#notification"></use>
                  </svg>
                  @if ($unreadNotificationCount > 0)
                    <span class="badge rounded-pill badge-success">{{ $unreadNotificationCount }}</span>
                  @endif
                </div>
                <div class="onhover-show-div notification-dropdown">
                  <h6 class="f-18 mb-0 dropdown-title">{{ __('Notifications') }}</h6>
                  <ul>
                    @forelse ($headerNotifications as $n)
                      @php
                          $data = $n->data;
                          $border = in_array($data['border'] ?? '', ['primary', 'success', 'warning', 'secondary', 'danger'], true)
                              ? $data['border']
                              : 'primary';
                          $notificationUrl = $data['url'] ?? (isset($data['application_id']) ? route('admin.host-applications.show', $data['application_id']) : null);
                      @endphp
                      <li class="b-l-{{ $border }} border-4 toast default-show-toast align-items-center text-light border-0 fade show" aria-live="polite" data-bs-autohide="false">
                        <div class="d-flex justify-content-between align-items-start gap-2 w-100">
                              @if ($notificationUrl)
                            <a href="{{ $notificationUrl }}" class="text-reset text-decoration-none flex-grow-1 min-w-0">
                              <div class="toast-body">
                                <p class="mb-0 f-w-600">{{ $data['title'] ?? '' }}</p>
                                <p class="mb-0 small" style="white-space: pre-wrap;">{{ $data['body'] ?? '' }}</p>
                              </div>
                            </a>
                          @else
                            <div class="toast-body flex-grow-1">
                              <p class="mb-0 f-w-600">{{ $data['title'] ?? '' }}</p>
                              <p class="mb-0 small" style="white-space: pre-wrap;">{{ $data['body'] ?? '' }}</p>
                            </div>
                          @endif
                          @if ($n->read_at === null)
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}" class="flex-shrink-0">
                              @csrf
                              <button class="btn-close btn-close-white m-auto" type="submit" aria-label="{{ __('Mark as read') }}"></button>
                            </form>
                          @endif
                        </div>
                      </li>
                    @empty
                      <li class="px-3 py-4 text-center text-muted small">{{ __('No notifications') }}</li>
                    @endforelse
                  </ul>
                </div>
              </li>
              <li class="profile-nav onhover-dropdown pe-0 py-0">
                <div class="d-flex profile-media align-items-center">
                  <span class="d-inline-flex align-items-center justify-content-center b-r-10 bg-light flex-shrink-0" style="width: 35px; height: 35px;" aria-hidden="true">
                    <i data-feather="user" style="width: 18px; height: 18px;"></i>
                  </span>
                  <div class="flex-grow-1"><span>{{ auth()->user()->name }}</span>
                    <p class="mb-0">{{ auth()->user()->getRoleNames()->first() ?? '—' }} <i class="middle fa-solid fa-angle-down"></i></p>
                  </div>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                  <li><a href="{{ route('profile.edit') }}"><i data-feather="user"></i><span>{{ __('Account') }}</span></a></li>
                  @role('Administrator')
                  <li><a href="{{ route('admin.settings.edit') }}"><i data-feather="settings"></i><span>{{ __('Settings') }}</span></a></li>
                  @endrole
                  <li>
                    <form action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-link text-start text-body w-100 py-0 px-0 border-0 text-decoration-none">
                        <i data-feather="log-in"></i><span>{{ __('Log out') }}</span>
                      </button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      <!-- Page Header Ends                              -->

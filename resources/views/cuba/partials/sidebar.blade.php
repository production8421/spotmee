<!-- Page Sidebar Start-->
        @php
            $authUser = auth()->user();
            $subscriberOnlySidebar = $authUser
                && $authUser->hasRole('Subscriber')
                && ! $authUser->hasRole('Administrator')
                && ! $authUser->hasRole('Host');
            $subscriberHomeHref = $subscriberOnlySidebar
                ? route('dashboard').'#subscriber-gym-bookings'
                : route('dashboard');
        @endphp
        <div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
          <div>
            <div class="logo-wrapper"><a href="{{ $subscriberHomeHref }}">@include('cuba.partials.brand-header-images')</a>
              <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="{{ $subscriberHomeHref }}"><img class="img-fluid" src="{{ $_brandHeaderIcon }}" alt="" style="max-height: 36px; width: auto; object-fit: contain;"></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn">
                    <div class="mobile-back text-end"><span>Back</span><i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  <li class="pin-title sidebar-main-title">
                    <div> 
                      <h6>Pinned</h6>
                    </div>
                  </li>
                  @unless ($subscriberOnlySidebar)
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-home"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-home"></use>
                      </svg><span>Dashboard</span></a></li>
                  @endunless
                  @role('Subscriber')
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard') }}#subscriber-gym-bookings">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-calendar"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-calender"></use>
                      </svg><span>{{ __('My gym bookings') }}</span></a></li>
                  @endrole
                  @role('Host')
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('host.gym-listings.index') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-ecommerce"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-ecommerce"></use>
                      </svg><span>{{ __('My gym listings') }}</span></a></li>
                  @endrole
                  @role('Administrator')
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.users.index') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-user"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-user"></use>
                      </svg><span>{{ __('Users') }}</span></a></li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.host-applications.index') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-contact"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-contact"></use>
                      </svg><span>{{ __('Pending Host request') }}</span></a></li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title" href="#" role="button">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-ecommerce"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-ecommerce"></use>
                      </svg><span>{{ __('Gym Listings') }}</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="{{ route('admin.gym-listings.index') }}"><span>{{ __('Listing') }}</span></a></li>
                      <li><a href="{{ route('admin.gym-listings.settings.edit') }}"><span>{{ __('Settings') }}</span></a></li>
                      <li><a href="{{ route('admin.coupons.index') }}"><span>{{ __('Coupons') }}</span></a></li>
                    </ul>
                  </li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.gym-bookings.index') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-calendar"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-calender"></use>
                      </svg><span>{{ __('Gym Bookings') }}</span></a></li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.notifications.index') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#notification"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#notification"></use>
                      </svg><span>{{ __('Notifications') }}</span></a></li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.media.index') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-file"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-file"></use>
                      </svg><span>{{ __('Media') }}</span></a></li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title" href="#" role="button">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-landing-page"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-landing-page"></use>
                      </svg><span>{{ __('Frontend') }}</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="{{ route('admin.frontend.home') }}"><span>{{ __('Home') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.how-it-works') }}"><span>{{ __('How It Works') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.find-a-gym') }}"><span>{{ __('Find a Gym') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.become-a-host') }}"><span>{{ __('Become a Host') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.faq') }}"><span>{{ __('FAQ') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.contact') }}"><span>{{ __('Contact') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.waiver-of-liability-host') }}"><span>{{ __('Waiver of Liability Host') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.waiver-of-liability-user') }}"><span>{{ __('Waiver of Liability User') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.cancellation-policy') }}"><span>{{ __('Cancellation Policy') }}</span></a></li>
                      <li><a href="{{ route('admin.frontend.footer') }}"><span>{{ __('Footer') }}</span></a></li>
                    </ul>
                  </li>
                  <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.settings.edit') }}">
                      <svg class="stroke-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#stroke-layout"></use>
                      </svg>
                      <svg class="fill-icon">
                        <use href="{{ $cubaAsset('svg/icon-sprite.svg') }}#fill-layout"></use>
                      </svg><span>{{ __('Settings') }}</span></a></li>
                  @endrole
                </ul>
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
        
        <!-- Page Sidebar Ends-->

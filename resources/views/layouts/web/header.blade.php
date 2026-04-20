@php
  $settings = $settings ?? \App\Models\ApplicationSetting::instance();
  $headerLogoUrl = $settings->headerLogoUrl() ?? asset('images/header-logo.png');
@endphp
<header class="relative w-full mt-10 z-50">
    <div class="mx-auto px-4">
      <div class="flex items-center justify-between h-16">
  
        <div class="flex items-center">
         <a href="{{ route('home') }}">
            <img src="{{ $headerLogoUrl }}" alt="{{ config('app.name') }} Logo" class="max-w-[140px] ml-[20px] w-auto">
         </a>
        </div>
  
        <!-- Desktop Menu (≥1024px) -->
        <nav class="hidden lg:flex space-x-8 text-[20px] font-regular text-[#333333] mb-[40px]">
          <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
          <a href="{{ route('how-it-works') }}" class="hover:text-blue-600">How It Works</a>
          <a href="{{ route('find-a-gym') }}" class="hover:text-blue-600">Find a Gym</a>
          <a href="{{ route('become-a-host') }}" class="hover:text-blue-600">Become a Host</a>
          <a href="{{ route('about') }}" class="hover:text-blue-600">About Us</a>
          <a href="{{ route('blog') }}" class="hover:text-blue-600">Blog</a>
          <a href="{{ route('contact') }}" class="hover:text-blue-600">Contact</a>
        </nav>
  
        <!-- Desktop Buttons (≥1024px) -->
        <div class="hidden lg:flex items-center gap-6 mb-[40px]">
          @php
            $user = auth()->user();
          @endphp
          @if ($user)
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="text-[20px] font-regular text-[var(--text-color)] hover:text-blue-600">
                Logout
              </button>
            </form>
            <a href="{{ route('dashboard') }}" class="sign-up-btn">Dashboard</a>
          @else
          <a href="{{ route('login') }}" class="text-[20px] font-regular text-[var(--text-color)] hover:text-blue-600">Login</a>
          <a href="#" class="sign-up-btn">Sign Up</a>
          @endif

        </div>
  
        <!-- Hamburger (<1024px) -->
        <button id="menuBtn" class="lg:hidden text-2xl">☰</button>
  
      </div>
    </div>
  
    <!-- Mobile Menu (<1024px) -->
    <div id="mobileMenu" class="hidden lg:hidden mt-10">
      <nav class="flex flex-col space-y-4 p-2 text-[20px] font-regular text-[var(--text-color)]">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('how-it-works') }}">How It Works</a>
        <a href="{{ route('find-a-gym') }}">Find a Gym</a>
        <a href="{{ route('become-a-host') }}">Become a Host</a>
        <a href="{{ route('about') }}">About Us</a>
        <a href="{{ route('blog') }}">Blog</a>
        <a href="{{ route('contact') }}">Contact</a>
        @if ($user)
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
          </form>
          <a href="{{ route('dashboard') }}" class="bg-[#4682B4] text-white text-center py-2 rounded-full">
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}">Login</a>
          <a href="#" class="bg-[#4682B4] text-white text-center py-2 rounded-full">
            Sign Up
          </a>
        @endif
      </nav>
    </div>
  </header>
  
  <script>
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
  
    menuBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
  
      if (menuBtn.innerText === "☰") {
        menuBtn.innerText = "✕";
      } else {
        menuBtn.innerText = "☰";
      }
    });
  </script>
  
<div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      {{-- Left side: Breadcrumbs and Page Title --}}
      <div class="flex items-center space-x-4">
        {{-- Breadcrumbs --}}
        <nav class="flex" aria-label="Breadcrumb">
          <ol class="flex items-center space-x-2">
            <li>
              <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <i class="fas fa-home"></i>
              </a>
            </li>
            @if(isset($breadcrumbs))
            @foreach($breadcrumbs as $breadcrumb)
            <li>
              <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                @if(isset($breadcrumb['url']))
                <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                  {{ $breadcrumb['title'] }}
                </a>
                @else
                <span class="text-gray-900 dark:text-white font-medium">{{ $breadcrumb['title'] }}</span>
                @endif
              </div>
            </li>
            @endforeach
            @else
            <li>
              <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900 dark:text-white font-medium">{{ $pageTitle ?? 'Page' }}</span>
              </div>
            </li>
            @endif
          </ol>
        </nav>
      </div>

      {{-- Right side: Actions --}}
      <div class="flex items-center space-x-4">
        @if(isset($actions))
        @foreach($actions as $action)
        <a href="{{ $action['url'] }}"
          class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          @if(isset($action['icon']))
          <i class="{{ $action['icon'] }} mr-2"></i>
          @endif
          {{ $action['title'] }}
        </a>
        @endforeach
        @endif

        {{-- User Profile Dropdown --}}
        <div class="relative">
          <button type="button" class="flex items-center space-x-3 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu" aria-expanded="false" aria-haspopup="true">
            <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
              <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="hidden md:block text-left">
              <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>
            <i class="fas fa-chevron-down text-gray-400"></i>
          </button>

          <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
            <div class="py-1" role="none">
              <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                <i class="fas fa-user mr-3"></i>
                Profile
              </a>
              <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
              </a>
              <div class="border-t border-gray-200 dark:border-gray-600"></div>
              <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                  <i class="fas fa-sign-out-alt mr-3"></i>
                  Sign out
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // User Menu Dropdown
    const userMenuButton = document.getElementById('user-menu');
    const userMenuDropdown = userMenuButton.nextElementSibling;

    userMenuButton.addEventListener('click', function() {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !expanded);
      userMenuDropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      if (!userMenuButton.contains(event.target)) {
        userMenuDropdown.classList.add('hidden');
        userMenuButton.setAttribute('aria-expanded', 'false');
      }
    });
  });
</script>
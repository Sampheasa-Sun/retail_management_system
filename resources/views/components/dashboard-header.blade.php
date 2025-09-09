<div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      {{-- Left side: Breadcrumbs and Page Title --}}
      <div class="flex items-center space-x-4" x-data>
        {{-- Sidebar Toggle Button (Mobile/Desktop) --}}
        <button @click="$dispatch('toggle-aside')" class="inline-flex items-center px-2 py-2 rounded-md border border-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 focus:outline-none mr-2" aria-label="Toggle sidebar">
          <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
        </button>
        {{-- Breadcrumbs --}}
        <nav class="flex" aria-label="Breadcrumb">
          <ol class="flex items-center space-x-2">
            <li>
              <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <i class="fas fa-home"></i>
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900 dark:text-white font-medium">{{ $pageTitle ?? 'Dashboard' }}</span>
              </div>
            </li>
          </ol>
        </nav>
      </div>


      {{-- Right side: Actions and User Menu --}}
      <div class="flex items-center space-x-4">
        {{-- Theme Toggle Button --}}
        <div x-data="{
            dark: localStorage.getItem('theme') === 'dark',
            toggleTheme() {
                this.dark = !this.dark;
                if (this.dark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            },
            init() {
                if (localStorage.getItem('theme') === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }" x-init="init()">
          <button @click="toggleTheme" class="inline-flex items-center px-2 py-2 rounded-md border border-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 focus:outline-none" :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
            <template x-if="!dark">
              <span class="flex items-center"><i class="fas fa-moon text-gray-600"></i></span>
            </template>
            <template x-if="dark">
              <span class="flex items-center"><i class="fas fa-sun text-yellow-400"></i></span>
            </template>
          </button>
        </div>



        {{-- Notifications --}}
        <div class="relative">
          <button type="button" class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="notifications-menu" aria-expanded="false" aria-haspopup="true">
            <span class="sr-only">View notifications</span>
            <i class="fas fa-bell"></i>
            @if(($pendingLoans ?? 0) > 0)
            <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
              {{ min(($pendingLoans ?? 0), 9) }}
            </span>
            @endif
          </button>

          <div class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="notifications-menu">
            <div class="py-1" role="none">
              <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600">
                <strong>Notifications</strong>
              </div>
              @if(($pendingLoans ?? 0) > 0)
              <a href="{{ route('loans.index') }}?status=pending" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                <i class="fas fa-clock text-yellow-500 mr-3"></i>
                {{ $pendingLoans }} pending loan{{ $pendingLoans > 1 ? 's' : '' }} require{{ $pendingLoans > 1 ? '' : 's' }} review
              </a>
              @endif
              @if(($overdueLoans ?? 0) > 0)
              <a href="{{ route('loans.index') }}?status=overdue" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600" role="menuitem">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                {{ $overdueLoans }} overdue loan{{ $overdueLoans > 1 ? 's' : '' }}
              </a>
              @endif
              @if(($pendingLoans ?? 0) == 0 && ($overdueLoans ?? 0) == 0)
              <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                No new notifications
              </div>
              @endif
            </div>
          </div>
        </div>

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
    // Quick Actions Dropdown
    const quickActionsButton = document.getElementById('quick-actions-menu');
    const quickActionsDropdown = quickActionsButton.nextElementSibling;

    quickActionsButton.addEventListener('click', function() {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !expanded);
      quickActionsDropdown.classList.toggle('hidden');
    });

    // Notifications Dropdown
    const notificationsButton = document.getElementById('notifications-menu');
    const notificationsDropdown = notificationsButton.nextElementSibling;

    notificationsButton.addEventListener('click', function() {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !expanded);
      notificationsDropdown.classList.toggle('hidden');
    });

    // User Menu Dropdown
    const userMenuButton = document.getElementById('user-menu');
    const userMenuDropdown = userMenuButton.nextElementSibling;

    userMenuButton.addEventListener('click', function() {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !expanded);
      userMenuDropdown.classList.toggle('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
      if (!quickActionsButton.contains(event.target)) {
        quickActionsDropdown.classList.add('hidden');
        quickActionsButton.setAttribute('aria-expanded', 'false');
      }
      if (!notificationsButton.contains(event.target)) {
        notificationsDropdown.classList.add('hidden');
        notificationsButton.setAttribute('aria-expanded', 'false');
      }
      if (!userMenuButton.contains(event.target)) {
        userMenuDropdown.classList.add('hidden');
        userMenuButton.setAttribute('aria-expanded', 'false');
      }
    });
  });
</script>
{{--
  This sidebar uses Tailwind CSS for styling and Alpine.js for the dropdown interaction.
  Make sure you have Alpine.js installed in your project.
  You can add it via CDN by placing this in your main layout file's <head>:
  <script src="//unpkg.com/alpinejs" defer></script>
--}}
<aside
    class="w-64 min-h-screen bg-white dark:bg-gray-800 shadow-md flex flex-col p-4 fixed top-0 left-0 z-40 transition-transform duration-300 md:translate-x-0"
    x-data="{
        dark: localStorage.getItem('theme') === 'dark',
        open: true,
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
            // Listen for sidebar toggle event
            window.addEventListener('toggle-aside', () => {
                this.open = !this.open;
            });
        }
    }"
    x-init="init()"
    :class="{'-translate-x-full': !open, 'translate-x-0': open}"
    style="z-index: 40;">

    {{-- Logo/Brand Name --}}
    <a href="{{ route('dashboard') }}" class="flex items-center pb-4 border-b border-gray-200 dark:border-gray-700">
        <i class="fas fa-layer-group text-2xl text-indigo-600 mr-3"></i>
        <span class="text-2xl font-bold text-gray-800 dark:text-gray-100">LoanManager</span>
    </a>

    {{-- Navigation Links --}}
    <nav class="mt-6 flex-grow">
        <ul class="flex flex-col gap-2">
            <li>
                {{-- Helper for active classes --}}
                @php
                $activeClasses = 'bg-indigo-600 text-white shadow-sm';
                $inactiveClasses = 'text-gray-600 hover:bg-gray-100 hover:text-gray-800 dark:text-gray-300 dark:hover:bg-gray-700';
                $activeSubClasses = 'bg-indigo-500 text-white';
                $inactiveSubClasses = 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700';
                @endphp

                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
            </li>

            <!-- === MODIFIED LOANS DROPDOWN === -->
            @php
            // The dropdown is active if any of the 'loans.*' routes are active.
            $isLoansActive = request()->routeIs('loans.*');
            @endphp
            <li x-data="{ open: {{ $isLoansActive ? 'true' : 'false' }} }">
                {{-- Dropdown Trigger --}}
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-colors duration-200 {{ $isLoansActive ? $activeClasses : $inactiveClasses }}">
                    <span class="flex items-center">
                        <i class="fas fa-list w-6 text-center"></i>
                        <span class="ml-3 font-medium">Loans</span>
                    </span>
                    <i class="fas fa-chevron-down transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>

                {{-- Submenu --}}
                <ul x-show="open" x-transition class="mt-2 flex flex-col gap-2 pl-5">
                    <li>
                        <a href="{{ route('loans.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-colors duration-200 {{ request()->routeIs('loans.index') ? $activeSubClasses : $inactiveSubClasses }}">
                            All Loans
                        </a>
                    </li>
                    <li>
                        {{-- === THIS IS THE NEW, CORRECTED LINK === --}}
                        <a href="{{ route('loans.today') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-colors duration-200 {{ request()->routeIs('loans.today') ? $activeSubClasses : $inactiveSubClasses }}">
                            Today's Loan
                        </a>
                    </li>
                </ul>
            </li>
            <!-- === END OF MODIFIED DROPDOWN === -->

            <li>
                <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('customers.*') ? $activeClasses : $inactiveClasses }}">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-3 font-medium">Borrowers</span>
                </a>
            </li>
            <li>
                <a href="{{ route('loans.create') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('loans.create') ? $activeClasses : $inactiveClasses }}">
                    <i class="fas fa-plus-circle w-6 text-center"></i>
                    <span class="ml-3 font-medium">New Loan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('reports.*') ? $activeClasses : $inactiveClasses }}">
                    <i class="fas fa-chart-line w-6 text-center"></i>
                    <span class="ml-3 font-medium">Reports</span>
                </a>
            </li>

            <li>
                <a href="{{ route('loan-logs.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('loan-logs.index') ? $activeClasses : $inactiveClasses }}">
                    <i class="fas fa-history w-6 text-center"></i>
                    <span class="ml-3 font-medium">Loan Log</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- User Dropdown Menu --}}
    <div x-data="{ open: false }" class="relative border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
        {{-- Dropdown Trigger --}}
        <button @click="open = !open" @click.away="open = false" class="w-full flex items-center text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
            <i class="fas fa-user-circle text-4xl text-gray-500 mr-3"></i>
            <div class="flex-grow">
                <span class="font-semibold text-gray-800 dark:text-gray-100 block">{{ Auth::user()->name ?? 'Admin User' }}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">Administrator</span>
            </div>
            <i class="fas fa-chevron-up transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
        </button>

        {{-- Dropdown Panel --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute bottom-full w-full mb-2 bg-white dark:bg-gray-700 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
            style="display: none;">
            <div class="py-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <i class="fas fa-user-shield w-6 text-gray-500 mr-2"></i> Profile
                </a>
                <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <i class="fas fa-sign-out-alt w-6 text-gray-500 mr-2"></i> Logout
                    </a>
                </form>
            </div>
        </div>
    </div>
</aside>
<nav x-data="{ open: false, darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => {
        localStorage.setItem('theme', val ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', val);
    })" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @elseif(auth()->user()->role === 'guru')
                        <a href="{{ route('guru.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @else
                        <a href="{{ route('siswa.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @endif
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-nav-link>
                    @elseif(auth()->user()->role === 'guru')
                        <x-nav-link :href="route('guru.dashboard')" :active="request()->routeIs('guru.dashboard')">
                            Dashboard
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                            Dashboard
                        </x-nav-link>
                    @endif

                    {{-- Menu khusus Admin --}}
                    @if(auth()->user()->role == 'admin')
                        <x-nav-link :href="route('admin.import.index')" :active="request()->routeIs('admin.import.*')">
                            Import User
                        </x-nav-link>
                        <x-nav-link :href="route('admin.rombels.index')" :active="request()->routeIs('admin.rombels.*')">
                            Kelola Rombel
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')"
                            :active="request()->routeIs('admin.categories.*')">
                            Kelola Kategori
                        </x-nav-link>
                        <x-nav-link :href="route('admin.books.index')" :active="request()->routeIs('admin.books.*')">
                            Kelola Buku
                        </x-nav-link>
                        <x-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*')">
                            Peminjaman
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Laporan
                        </x-nav-link>
                    @endif

                    {{-- Menu Guru --}}
                    @if(auth()->user()->role == 'guru')
                        <x-nav-link :href="route('guru.books.index')" :active="request()->routeIs('guru.books.*')">
                            Daftar Buku
                        </x-nav-link>
                        <x-nav-link :href="route('guru.loans.history')" :active="request()->routeIs('guru.loans.*')">
                            Peminjaman Saya
                        </x-nav-link>
                    @endif

                    {{-- Menu Siswa --}}
                    @if(auth()->user()->role == 'siswa')
                        <x-nav-link :href="route('siswa.books.index')" :active="request()->routeIs('siswa.books.*')">
                            Daftar Buku
                        </x-nav-link>
                        <x-nav-link :href="route('siswa.loans.history')" :active="request()->routeIs('siswa.loans.*')">
                            Peminjaman Saya
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Tombol Toggle Tema -->
                <button id="theme-toggle" class="relative inline-flex h-9 w-9 items-center justify-center rounded-full
               bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200
               hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 shadow-md">
                    <!-- Icon Sun -->
                    <svg id="icon-sun" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 15a5 5 0 100-10 5 5 0 000 10zm0 2a7 7 0 110-14 7 7 0 010 14zm0-16a1 1 0 011 1v1a1 1 0 11-2 0V2a1 1 0 011-1zm0 18a1 1 0 011-1v-1a1 1 0 11-2 0v1a1 1 0 011 1zm8-9a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-16 0a1 1 0 011-1H2a1 1 0 110 2h1a1 1 0 01-1-1zm12.071 6.071a1 1 0 01-1.414 0L12 15.414a1 1 0 011.414-1.414l1.657 1.657a1 1 0 010 1.414zM6.343 6.343A1 1 0 014.93 4.93L6.586 3.272A1 1 0 118 4.686L6.343 6.343zm0 7.314a1 1 0 010 1.414L4.686 18a1 1 0 01-1.414-1.414l1.657-1.657a1 1 0 011.414 0zm9.9-9.9a1 1 0 010 1.414L15.414 8A1 1 0 0114 6.586l1.657-1.657a1 1 0 011.414 0z" />
                    </svg>

                    <!-- Icon Moon -->
                    <svg id="icon-moon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 116.707 2.707a8.001 8.001 0 0010.586 10.586z" />
                    </svg>
                </button>

                <!-- Dropdown User -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
            @elseif(auth()->user()->role === 'guru')
                <x-responsive-nav-link :href="route('guru.dashboard')" :active="request()->routeIs('guru.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const iconSun = document.getElementById('icon-sun');
        const iconMoon = document.getElementById('icon-moon');
        const html = document.documentElement;

        function updateIcon() {
            if (html.classList.contains('dark')) {
                iconMoon.classList.add('hidden');
                iconSun.classList.remove('hidden');
            } else {
                iconSun.classList.add('hidden');
                iconMoon.classList.remove('hidden');
            }
        }

        // set tema awal
        if (localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        updateIcon();

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            if (html.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
            updateIcon();
        });
    </script>
</nav>
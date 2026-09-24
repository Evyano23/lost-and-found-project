<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-teal-600 dark:text-teal-400 tracking-tight">
                        Trove
                    </a>
                </div>

                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                        {{ __('Feed') }}
                    </x-nav-link>
                    <x-nav-link :href="route('items.map')" :active="request()->routeIs('items.map')">
                        {{ __('🗺️ Map') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('items.create')" :active="request()->routeIs('items.create')">
                            {{ __('+ Report Item') }}
                        </x-nav-link>
                        <x-nav-link :href="route('items.my-items')" :active="request()->routeIs('items.my-items')">
                            {{ __('My Reports') }}
                        </x-nav-link>
                        <x-nav-link :href="route('claims.my-claims')" :active="request()->routeIs('claims.my-claims')">
                            {{ __('My Claims') }}
                        </x-nav-link>
                        @if (auth()->user()->is_admin)
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                {{ __('🛡️ Admin Panel') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-3">
                <button
                    x-data
                    @click="
                        document.documentElement.classList.toggle('dark');
                        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                    "
                    class="p-2 rounded-full text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    aria-label="Toggle dark mode"
                >
                    <svg x-show="!document.documentElement.classList.contains('dark')" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg x-show="document.documentElement.classList.contains('dark')" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>

                @auth
                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 rounded-full text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none" aria-label="Notifications">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            @endif
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-2 z-50 overflow-hidden"
                             style="display: none;">

                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Notifications</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Mark all read</button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/50">
                                @forelse(Auth::user()->notifications->take(10) as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition {{ $notification->read_at ? 'opacity-60' : 'bg-indigo-50/30 dark:bg-indigo-900/10' }}">
                                        <p class="text-xs font-medium text-slate-800 dark:text-slate-200">
                                            {{ $notification->data['message'] ?? 'New notification' }}
                                        </p>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-xs text-slate-400 dark:text-slate-500">
                                        No notifications yet
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-base font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">Log in</a>
                        <a href="{{ route('register') }}" class="text-base font-semibold px-4 py-2 rounded-full bg-teal-600 text-white hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow transition">Register</a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button
                    x-data
                    @click="
                        document.documentElement.classList.toggle('dark');
                        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                    "
                    class="p-2 mr-1 rounded-full text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    aria-label="Toggle dark mode"
                >
                    <svg x-show="!document.documentElement.classList.contains('dark')" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg x-show="document.documentElement.classList.contains('dark')" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200 dark:border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                {{ __('Feed') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('items.map')" :active="request()->routeIs('items.map')">
                {{ __('🗺️ Map') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('items.create')" :active="request()->routeIs('items.create')">
                    {{ __('+ Report Item') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('items.my-items')" :active="request()->routeIs('items.my-items')">
                    {{ __('My Reports') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('claims.my-claims')" :active="request()->routeIs('claims.my-claims')">
                    {{ __('My Claims') }}
                </x-responsive-nav-link>
                @if (auth()->user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('🛡️ Admin Panel') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-slate-200 dark:border-slate-800">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 space-y-2">
                    <a href="{{ route('login') }}" class="block text-sm font-semibold text-slate-600 dark:text-slate-400">Log in</a>
                    <a href="{{ route('register') }}" class="block text-sm font-semibold text-teal-600 dark:text-teal-400">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
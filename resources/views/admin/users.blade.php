<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Admin - User Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Admin Nav -->
            <div class="flex space-x-2 border-b border-slate-200 dark:border-slate-700 pb-3 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Overview</a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium shadow-sm whitespace-nowrap">Users</a>
                <a href="{{ route('admin.items') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Items Moderation</a>
                <a href="{{ route('admin.claims') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Claims Oversight</a>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Reports Queue</a>
            </div>

            <!-- Search Bar -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user name or email..." class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-indigo-500">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Search</button>
                </form>
            </div>

            <!-- Flash messages -->
            @if(session('status'))
                <div class="p-4 bg-emerald-50 text-emerald-700 rounded-lg text-sm">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-rose-50 text-rose-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <!-- Users Table -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 uppercase text-slate-500 font-semibold border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="p-4">User</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Trust Metrics</th>
                                <th class="p-4">Activity</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                                    <td class="p-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-100 text-sm">{{ $user->name }}</div>
                                        <div class="text-slate-400">{{ $user->email }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if($user->is_admin)
                                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 rounded font-semibold">Admin</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded">User</span>
                                        @endif
                                    </td>
                                    <td class="p-4 space-y-0.5">
                                        <div>Reports Against: <strong class="{{ $user->reports_against_count > 0 ? 'text-rose-600' : 'text-slate-600' }}">{{ $user->reports_against_count ?? 0 }}</strong></div>
                                        <div>Returns: <strong class="text-emerald-600">{{ $user->successful_returns_count ?? 0 }}</strong></div>
                                    </td>
                                    <td class="p-4 space-y-0.5">
                                        <div>Items: {{ $user->items_count }}</div>
                                        <div>Claims: {{ $user->claims_count }}</div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.toggleAdmin', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-medium border border-slate-300 dark:border-slate-600 rounded hover:bg-slate-100 dark:hover:bg-slate-700">
                                                        {{ $user->is_admin ? 'Demote' : 'Make Admin' }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Delete this user account?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2.5 py-1 text-[11px] font-medium bg-rose-600 text-white rounded hover:bg-rose-700">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-slate-400 italic">You</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
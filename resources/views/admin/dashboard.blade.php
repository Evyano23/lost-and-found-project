<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Admin Navigation Tabs -->
            <div class="flex space-x-2 border-b border-slate-200 dark:border-slate-700 pb-3 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium shadow-sm whitespace-nowrap">Overview</a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Users</a>
                <a href="{{ route('admin.items') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Items Moderation</a>
                <a href="{{ route('admin.claims') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Claims Oversight</a>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Reports Queue</a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Total Users</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Total Items</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $stats['total_items'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Active Items</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['active_items'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Total Claims</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $stats['total_claims'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Pending Claims</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending_claims'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Open Reports</p>
                    <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">{{ $stats['unresolved_reports'] }}</p>
                </div>
            </div>

            <!-- Recent Activity Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Items -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">Recently Listed Items</h3>
                    <div class="space-y-3">
                        @forelse($recent_items as $item)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                                <div>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $item->type === 'lost' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ ucfirst($item->type) }}
                                    </span>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200 mt-1">{{ $item->title }}</p>
                                    <p class="text-xs text-slate-400">By {{ $item->user->name ?? 'Anonymous' }} • {{ $item->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('items.show', $item) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View</a>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">No recent items.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Reports -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-4">Pending Flagged Reports</h3>
                    <div class="space-y-3">
                        @forelse($recent_reports as $report)
                            <div class="p-3 rounded-lg bg-rose-50/50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30 flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-semibold text-rose-700 dark:text-rose-400 uppercase">Reason: {{ $report->reason }}</span>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">
                                        Reported: <strong>{{ $report->reportedUser->name ?? 'User' }}</strong> by {{ $report->reporter->name ?? 'User' }}
                                    </p>
                                </div>
                                <span class="text-[10px] text-slate-400">{{ $report->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">No open reports pending.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
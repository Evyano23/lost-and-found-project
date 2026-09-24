<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Admin - Item Moderation') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Admin Nav -->
            <div class="flex space-x-2 border-b border-slate-200 dark:border-slate-700 pb-3 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Overview</a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Users</a>
                <a href="{{ route('admin.items') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium shadow-sm whitespace-nowrap">Items Moderation</a>
                <a href="{{ route('admin.claims') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Claims Oversight</a>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Reports Queue</a>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                <form method="GET" action="{{ route('admin.items') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item titles..." class="w-full text-sm rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-indigo-500">
                    <select name="status" class="text-sm rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending_claim" {{ request('status') === 'pending_claim' ? 'selected' : '' }}>Pending Claim</option>
                        <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Filter</button>
                </form>
            </div>

            @if(session('status'))
                <div class="p-4 bg-emerald-50 text-emerald-700 rounded-lg text-sm">{{ session('status') }}</div>
            @endif

            <!-- Items Table -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 uppercase text-slate-500 font-semibold border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="p-4">Item Details</th>
                                <th class="p-4">Reporter</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Date</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($items as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                                    <td class="p-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-100 text-sm">{{ $item->title }}</div>
                                        <span class="text-[10px] uppercase font-semibold px-1.5 py-0.5 rounded {{ $item->type === 'lost' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $item->type }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        {{ $item->is_anonymous ? 'Anonymous User' : ($item->user->name ?? 'Deleted User') }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold uppercase bg-slate-100 dark:bg-slate-700">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-400">
                                        {{ $item->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('items.show', $item) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">View</a>
                                            <form action="{{ route('admin.items.delete', $item) }}" method="POST" onsubmit="return confirm('Remove this item listing permanently?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-rose-600 text-white rounded text-[11px]">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $items->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
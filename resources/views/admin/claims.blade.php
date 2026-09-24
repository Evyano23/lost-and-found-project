<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Admin - Claims Oversight') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Admin Nav -->
            <div class="flex space-x-2 border-b border-slate-200 dark:border-slate-700 pb-3 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Overview</a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Users</a>
                <a href="{{ route('admin.items') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Items Moderation</a>
                <a href="{{ route('admin.claims') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium shadow-sm whitespace-nowrap">Claims Oversight</a>
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-sm font-medium whitespace-nowrap">Reports Queue</a>
            </div>

            <!-- Claims Table -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 uppercase text-slate-500 font-semibold border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="p-4">Item</th>
                                <th class="p-4">Claimant</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Proof Note</th>
                                <th class="p-4 text-right">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($claims as $claim)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30">
                                    <td class="p-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-100 text-sm">{{ $claim->item->title ?? 'Deleted Item' }}</div>
                                    </td>
                                    <td class="p-4">
                                        {{ $claim->claimant->name ?? 'Unknown User' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $claim->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($claim->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $claim->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 max-w-xs truncate text-slate-500">
                                        {{ $claim->proof_description ?? 'No text provided' }}
                                    </td>
                                    <td class="p-4 text-right text-slate-400">
                                        {{ $claim->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">No claims submitted yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $claims->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
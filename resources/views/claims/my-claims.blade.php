<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('My Submitted Claims') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Target Item</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Your Proof Description</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Claim Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Submitted Date</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse ($claims as $claim)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/30 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('items.show', $claim->item) }}" class="font-bold text-teal-600 dark:text-teal-400 hover:underline">
                                        {{ $claim->item->title }}
                                    </a>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
                                        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $claim->item->location_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-700 dark:text-slate-300 max-w-xs truncate">
                                    {{ $claim->proof_description }}
                                    @if ($claim->response_notes)
                                        <div class="text-xs text-teal-600 dark:text-teal-300 mt-1">
                                            <strong>Response:</strong> {{ $claim->response_notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $claim->status->value === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' : '' }}
                                        {{ $claim->status->value === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200' : '' }}
                                        {{ $claim->status->value === 'rejected' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' : '' }}
                                        {{ $claim->status->value === 'cancelled' ? 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' : '' }}
                                    ">
                                        {{ $claim->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $claim->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($claim->status->value === 'pending')
                                        <form method="POST" action="{{ route('claims.cancel', $claim) }}" onsubmit="return confirm('Withdraw this claim?');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-rose-600 dark:text-rose-400 hover:underline text-xs font-semibold">Withdraw</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">
                                    <svg class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    You haven't filed any ownership claims yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div>
                {{ $claims->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
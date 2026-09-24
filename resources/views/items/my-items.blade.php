<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ __('My Reported Items') }}
            </h2>
            <a href="{{ route('items.create') }}" class="inline-flex items-center px-5 py-2.5 bg-teal-600 rounded-full font-semibold text-sm text-white hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow-md transition">
                + Report New Item
            </a>
        </div>
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
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Item</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Type</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Category</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Claims</th>
                            <th class="px-6 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse ($items as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/30 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-slate-100">{{ $item->title }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
                                        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $item->location_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $item->type->value === 'lost' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' }}">
                                        {{ $item->type->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $item->category->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @class([
                                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' => $item->status->value === 'approved',
                                            'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' => $item->status->value === 'rejected',
                                            'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200' => in_array($item->status->value, ['pending_claim', 'pending']),
                                            'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' => !in_array($item->status->value, ['approved', 'rejected', 'pending_claim', 'pending']),
                                        ])">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('claims.item-claims', $item) }}" class="text-teal-600 dark:text-teal-400 hover:underline font-semibold text-xs">
                                        {{ $item->claims->count() }} claims
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('items.show', $item) }}" class="text-slate-600 dark:text-slate-400 hover:underline text-xs font-medium">View</a>
                                    <a href="{{ route('items.edit', $item) }}" class="text-teal-600 dark:text-teal-400 hover:underline text-xs font-medium">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">
                                    <svg class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    You haven't reported any items yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
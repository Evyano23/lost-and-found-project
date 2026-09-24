<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ __('Lost & Found Feed') }}
            </h2>
            @auth
                <a href="{{ route('items.create') }}" class="inline-flex items-center px-5 py-2.5 bg-teal-600 rounded-full font-semibold text-sm text-white hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow-md transition">
                    + Report Item
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search & Filters -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Keyword</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Type</label>
                        <select name="type" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">All Types</option>
                            <option value="lost" @selected(request('type') === 'lost')>Lost Items</option>
                            <option value="found" @selected(request('type') === 'found')>Found Items</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Category</label>
                        <select name="category_id" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Sort By</label>
                        <select name="sort" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest First</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2 md:col-span-4">
                        <button type="submit" class="px-6 py-2 bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 rounded-full text-sm font-semibold hover:-translate-y-0.5 hover:shadow-md transition">Filter</button>
                        <a href="{{ route('items.index') }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 rounded-full text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Items Feed -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
              @forelse ($items as $item)
    <div
        class="group bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-md border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col justify-between hover:-translate-y-0.5 transition animate-fadeInUp"
        style="opacity: 0; animation-delay: {{ $loop->index * 60 }}ms;"
    >
    @if ($item->primary_image)
        <div class="h-48 w-full overflow-hidden">
            <img src="{{ asset('storage/' . $item->primary_image) }}" alt="{{ $item->title }}" class="h-48 w-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
    @else
                            <div class="h-48 w-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400">No Photo</div>
                        @endif

                          <div class="p-5 flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $item->type->value === 'lost' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' }}">
                                    {{ $item->type->label() }}
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $item->category->name }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-slate-900 dark:text-slate-100 mb-1">
                                <a href="{{ route('items.show', $item) }}" class="hover:text-teal-600 dark:hover:text-teal-400 transition">{{ $item->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-4">{{ $item->description }}</p>
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1.5">
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $item->location_name }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $item->date_lost_or_found->format('M d, Y') }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    {{ $item->reporterName() }}
                                </div>
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full
                                @class([
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' => $item->status->value === 'approved',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' => $item->status->value === 'rejected',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200' => in_array($item->status->value, ['pending_claim', 'pending']),
                                    'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' => !in_array($item->status->value, ['approved', 'rejected', 'pending_claim', 'pending']),
                                ])">
                                {{ $item->status->label() }}
                            </span>
                            <a href="{{ route('items.show', $item) }}" class="text-sm font-semibold text-teal-600 dark:text-teal-400 hover:underline">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                        No reports matching your criteria found.
                    </div>
                @endforelse
            </div>

            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
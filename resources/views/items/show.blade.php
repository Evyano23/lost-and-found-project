<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ $item->title }}
            </h2>
            <div class="flex items-center space-x-2">
                @can('update', $item)
                    <a href="{{ route('items.edit', $item) }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-full text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition">Edit</a>
                @endcan
                @can('delete', $item)
                    <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Are you sure you want to delete this report?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-full text-xs font-semibold hover:bg-rose-700 transition">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Info & Photos -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center flex-wrap gap-2 mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $item->type->value === 'lost' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' }}">
                                {{ $item->type->label() }}
                            </span>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-teal-100 text-teal-700 dark:bg-teal-900/50 dark:text-teal-200">
                                {{ $item->category->name }}
                            </span>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full
                                @class([
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-200' => $item->status->value === 'approved',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-200' => $item->status->value === 'rejected',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200' => in_array($item->status->value, ['pending_claim', 'pending']),
                                    'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' => !in_array($item->status->value, ['approved', 'rejected', 'pending_claim', 'pending']),
                                ])">
                                {{ $item->status->label() }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">{{ $item->title }}</h1>

                        <!-- Photo Gallery -->
                        @if ($item->primary_image || $item->images->isNotEmpty())
                            <div x-data="{
                                mainImage: '{{ asset('storage/' . $item->primary_image) }}',
                                swap(thumbSrc, event) {
                                    const clickedThumb = event.target;
                                    const oldMain = this.mainImage;
                                    this.mainImage = thumbSrc;
                                    clickedThumb.src = oldMain;
                                }
                            }">
                                <div class="mb-4 rounded-2xl overflow-hidden max-h-96">
                                    <img :src="mainImage" alt="{{ $item->title }}" class="w-full h-full object-cover cursor-pointer">
                                </div>

                                @if ($item->images->isNotEmpty())
                                    <div class="grid grid-cols-4 gap-2 mb-6">
                                        @foreach ($item->images as $galleryImg)
                                            <img
                                                src="{{ asset('storage/' . $galleryImg->image_path) }}"
                                                alt="Gallery Image"
                                                class="h-20 w-full object-cover rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:opacity-80 transition"
                                                @click="swap($el.src, $event)"
                                            >
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="prose dark:prose-invert max-w-none mb-6">
                            <h3 class="text-sm font-semibold uppercase text-slate-500 dark:text-slate-400 tracking-wider">Description</h3>
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $item->description }}</p>
                        </div>

                        @if ($item->reward_amount)
                            <div class="flex items-center gap-2 p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-2xl text-sm text-amber-900 dark:text-amber-200">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span><strong>Reward Offered:</strong> ${{ number_format($item->reward_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar: Details & Claiming -->
                <div class="space-y-6">
                    <!-- Key Incident Details -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-sm space-y-4">
                        <h3 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-2">Incident Details</h3>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block text-xs mb-1">Date</span>
                            <span class="font-medium text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $item->date_lost_or_found->format('F d, Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block text-xs mb-1">Location</span>
                            <span class="font-medium text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $item->location_name }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block text-xs mb-1">Reported By</span>
                            <div class="font-medium text-slate-800 dark:text-slate-200 flex items-center gap-1.5 flex-wrap">
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                {{ $item->reporterName() }}
                                @if (!$item->is_anonymous && $item->user)
                                    <x-trust-badge :user="$item->user" />
                                @endif
                            </div>
                        </div>
                        @if (!$item->is_anonymous && ($item->contact_phone || $item->contact_email))
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-700 space-y-2">
                                <span class="text-slate-500 dark:text-slate-400 block text-xs">Direct Contact</span>
                                @if ($item->contact_phone)
                                    <div class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        {{ $item->contact_phone }}
                                    </div>
                                @endif
                                @if ($item->contact_email)
                                    <div class="text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ $item->contact_email }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Ownership & Claims Action Card -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 text-sm">
                        @auth
                            @if (auth()->id() === $item->user_id)
                                <h3 class="font-bold text-slate-900 dark:text-slate-100 mb-2">Reporter Management</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">You posted this item report.</p>
                                <a href="{{ route('claims.item-claims', $item) }}" class="block text-center w-full py-2.5 bg-teal-600 text-white rounded-full font-semibold text-xs uppercase tracking-wider hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow-md transition">
                                    Review Incoming Claims ({{ $item->claims()->count() }})
                                </a>
                            @elseif ($userClaim)
                                <h3 class="font-bold text-slate-900 dark:text-slate-100 mb-2">Your Claim Status</h3>
                                <div class="p-3 mb-3 rounded-xl text-xs font-semibold
                                    @class([
                                        'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200' => $userClaim->status->value === 'approved',
                                        'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200' => $userClaim->status->value === 'pending',
                                        'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-200' => $userClaim->status->value === 'rejected',
                                        'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300' => $userClaim->status->value === 'cancelled',
                                    ])">
                                    {{ $userClaim->status->label() }}
                                </div>
                                @if ($userClaim->status->value === 'pending')
                                    <form method="POST" action="{{ route('claims.cancel', $userClaim) }}">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                                            Withdraw / Cancel Claim
                                        </button>
                                    </form>
                                @endif
                            @elseif ($canClaim)
                                @include('partials.safety-notice')
                                <h3 class="font-bold text-slate-900 dark:text-slate-100 mb-2 mt-4">Claim This Item</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Provide proof of ownership (serial number, distinctive marks, contents) to contact the finder.</p>

                                @if ($errors->any())
                                    <div class="mb-3 p-3 bg-rose-50 dark:bg-rose-900/40 text-rose-800 dark:text-rose-200 rounded-xl border border-rose-100 dark:border-rose-800 text-xs">
                                        <p class="font-semibold mb-1">Please fix the following:</p>
                                        <ul class="list-disc pl-4 space-y-0.5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('claims.store', $item) }}" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Proof Description *</label>
                                        <textarea name="proof_description" required rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-xs focus:border-teal-500 focus:ring-teal-500" placeholder="Describe unique features, markings, or receipts..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Proof Photo / Receipt (Optional)</label>
                                        <input type="file" name="proof_image" accept="image/*" class="w-full text-xs text-slate-500 dark:text-slate-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Your Phone</label>
                                        <input type="text" name="contact_phone" value="{{ auth()->user()->phone }}" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-xs focus:border-teal-500 focus:ring-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Your Email</label>
                                        <input type="email" name="contact_email" value="{{ auth()->user()->email }}" class="w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-xs focus:border-teal-500 focus:ring-teal-500">
                                    </div>
                                    <button type="submit" class="w-full py-2.5 bg-teal-600 text-white rounded-full font-semibold text-xs uppercase tracking-wider hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow-md transition">
                                        Submit Ownership Claim
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-slate-500 dark:text-slate-400">This item is currently {{ $item->status->label() }} and not accepting claims.</p>
                            @endif
                        @else
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">Is this yours or did you find it?</p>
                            <a href="{{ route('login') }}" class="block text-center w-full py-2.5 bg-teal-600 text-white rounded-full font-semibold text-xs uppercase tracking-wider hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow-md transition">
                                Log in to Submit a Claim
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="text-center">
                @include('partials.report-modal', ['reportableType' => 'item', 'reportableId' => $item->id])
            </div>
        </div>
    </div>
</x-app-layout>
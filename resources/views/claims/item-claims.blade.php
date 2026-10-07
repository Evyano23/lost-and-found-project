<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Incoming Claims for: ') }} {{ $item->title }}
            </h2>
            <a href="{{ route('items.show', $item) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Back to Item
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse ($claims as $claim)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base">
                                    Claim by {{ $claim->user->name }}
                                </h3>
                                <div class="text-xs text-gray-500">
                                    Submitted {{ $claim->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $claim->status->value === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : '' }}
                                {{ $claim->status->value === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : '' }}
                                {{ $claim->status->value === 'rejected' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200' : '' }}
                                {{ $claim->status->value === 'cancelled' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                            ">
                                {{ $claim->status->label() }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Proof of Ownership Description:</h4>
                            <p class="text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900 p-3 rounded-md">{{ $claim->proof_description }}</p>
                        </div>

                        @if ($claim->verificationAnswers->isNotEmpty())
                            <div class="mb-4 p-4 bg-teal-50 dark:bg-teal-950/40 rounded-xl border border-teal-100 dark:border-teal-800">
                                <h4 class="text-xs font-bold text-teal-800 dark:text-teal-300 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    Verification Questions & Claimant Answers:
                                </h4>
                                <div class="space-y-2">
                                    @foreach ($claim->verificationAnswers as $ans)
                                        <div class="p-2.5 bg-white dark:bg-gray-900 rounded-lg border border-teal-100 dark:border-gray-800 text-xs">
                                            <p class="font-semibold text-gray-700 dark:text-gray-300 mb-0.5">
                                                Q: {{ $ans->question?->question ?? 'Verification Question' }}
                                            </p>
                                            <p class="text-teal-800 dark:text-teal-200 pl-2.5 border-l-2 border-teal-500 font-medium">
                                                A: {{ $ans->answer }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($claim->proof_image)
                            <div class="mb-4">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Uploaded Proof / Receipt Photo:</h4>
                                <a href="{{ asset('storage/' . $claim->proof_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $claim->proof_image) }}" alt="Proof Image" class="h-32 rounded border border-gray-200 dark:border-gray-700 object-cover">
                                </a>
                            </div>
                        @endif

                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-4 flex flex-wrap gap-4">
                            @if ($claim->contact_phone)
                                <div>📞 Phone: <strong>{{ $claim->contact_phone }}</strong></div>
                            @endif
                            @if ($claim->contact_email)
                                <div>✉️ Email: <strong>{{ $claim->contact_email }}</strong></div>
                            @endif
                        </div>

                        @if ($claim->response_notes)
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/40 rounded text-xs text-indigo-900 dark:text-indigo-200 mb-4">
                                <strong>Your Decision Note:</strong> {{ $claim->response_notes }}
                            </div>
                        @endif

                        @if ($claim->status->value === 'pending')
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center gap-3">
                                <!-- Approve Form -->
                                <form method="POST" action="{{ route('claims.approve', $claim) }}" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="text" name="notes" placeholder="Approval note (e.g. Verified serial)" class="text-xs rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 py-1">
                                    <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white rounded text-xs font-semibold hover:bg-emerald-500">
                                        Approve & Mark Item Claimed
                                    </button>
                                </form>

                                <!-- Reject Form -->
                                <form method="POST" action="{{ route('claims.reject', $claim) }}" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="text" name="notes" placeholder="Reason for rejection" class="text-xs rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 py-1">
                                    <button type="submit" class="px-4 py-1.5 bg-rose-600 text-white rounded text-xs font-semibold hover:bg-rose-500">
                                        Reject Claim
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-sm text-gray-500">
                        No claims submitted for this item yet.
                    </div>
                @endforelse
            </div>

            <div>
                {{ $claims->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

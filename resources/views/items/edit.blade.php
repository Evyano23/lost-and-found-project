<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Edit Item Report: ') }} {{ $item->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/40 text-rose-800 dark:text-rose-200 rounded-2xl border border-rose-100 dark:border-rose-800 text-sm">
                        <p class="font-semibold mb-1">Please fix the following:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-6"
                      x-data="{ selectedType: '{{ old('type', $item->type->value) }}' }">
                    @csrf
                    @method('PUT')

                    <!-- Type & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Type</label>
                            <select name="type" x-model="selectedType" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="lost" @selected(old('type', $item->type->value) === 'lost')>Lost Item</option>
                                <option value="found" @selected(old('type', $item->type->value) === 'found')>Found Item</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                            <select name="status" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="active" @selected(old('status', $item->status->value) === 'active')>Active</option>
                                <option value="pending_claim" @selected(old('status', $item->status->value) === 'pending_claim')>Claim Pending</option>
                                <option value="claimed" @selected(old('status', $item->status->value) === 'claimed')>Claimed</option>
                                <option value="resolved" @selected(old('status', $item->status->value) === 'resolved')>Resolved</option>
                                <option value="closed" @selected(old('status', $item->status->value) === 'closed')>Closed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Category & Title -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Category *</label>
                            <select name="category_id" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Item Title *</label>
                            <input type="text" name="title" value="{{ old('title', $item->title) }}" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Description *</label>
                        <textarea name="description" rows="4" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">{{ old('description', $item->description) }}</textarea>
                    </div>

                    <!-- Date & Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Date Incident Occurred *</label>
                            <input type="date" name="date_lost_or_found" value="{{ old('date_lost_or_found', $item->date_lost_or_found->format('Y-m-d')) }}" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location *</label>
                            <input type="text" name="location_name" value="{{ old('location_name', $item->location_name) }}" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                    </div>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $item->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $item->longitude) }}">

                    <!-- Photos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Replace Primary Photo</label>
                            @if ($item->primary_image)
                                <img src="{{ asset('storage/' . $item->primary_image) }}" alt="Current Photo" class="h-20 w-20 object-cover rounded-xl mb-2 border border-slate-200 dark:border-slate-600">
                            @endif
                            <input type="file" name="primary_image" accept="image/*" class="mt-1 w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-teal-50 file:text-teal-700 dark:file:bg-teal-900/40 dark:file:text-teal-300 hover:file:bg-teal-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Add Gallery Photos</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="mt-1 w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-teal-50 file:text-teal-700 dark:file:bg-teal-900/40 dark:file:text-teal-300 hover:file:bg-teal-100">
                        </div>
                    </div>

                    <!-- Ownership Verification Questions — Only shown for Found Items -->
                    <div x-show="selectedType === 'found'" x-transition
                         class="p-5 bg-teal-50/50 dark:bg-slate-900/60 rounded-2xl border border-teal-100 dark:border-teal-900/40 space-y-3"
                         x-data="{
                             questions: {{ json_encode(old('verification_questions', $item->verificationQuestions->pluck('question')->toArray() ?: [''])) }},
                             addQuestion() {
                                 if (this.questions.length < 5) {
                                     this.questions.push('');
                                 }
                             },
                             removeQuestion(index) {
                                 this.questions.splice(index, 1);
                                 if (this.questions.length === 0) {
                                     this.questions.push('');
                                 }
                             }
                         }">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-semibold text-teal-900 dark:text-teal-200 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    Ownership Verification Questions
                                </label>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Set questions only the real owner can answer. Claimants must answer these when claiming.
                                </p>
                            </div>
                            <button type="button" @click="addQuestion" x-show="questions.length < 5" class="text-xs px-3 py-1.5 bg-teal-600 text-white font-medium rounded-full hover:bg-teal-700 transition">
                                + Add Question
                            </button>
                        </div>

                        <div class="space-y-2 mt-2">
                            <template x-for="(q, index) in questions" :key="index">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-teal-700 dark:text-teal-400 w-6 text-center" x-text="(index + 1) + '.'"></span>
                                    <input type="text" name="verification_questions[]" x-model="questions[index]" placeholder="e.g. What is the unique engraving or brand mark?" class="flex-1 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-xs focus:border-teal-500 focus:ring-teal-500">
                                    <button type="button" @click="removeQuestion(index)" x-show="questions.length > 1" class="text-slate-400 hover:text-rose-500 p-1 text-sm font-bold" title="Remove question">&times;</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Reward & Anonymity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reward Offered ($)</label>
                            <input type="number" step="0.01" name="reward_amount" value="{{ old('reward_amount', $item->reward_amount) }}" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div class="pt-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous', $item->is_anonymous) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-teal-600 focus:ring-teal-500">
                                <span class="text-sm text-slate-700 dark:text-slate-300">Post Anonymously</span>
                            </label>
                        </div>
                    </div>

                    <!-- Desktop actions -->
                    <div class="hidden sm:flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('items.show', $item) }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 rounded-full text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-full text-sm shadow-sm hover:bg-teal-700 hover:-translate-y-0.5 hover:shadow-md transition">
                            Save Changes
                        </button>
                    </div>

                    <!-- Mobile sticky actions -->
                    <div class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-700 p-3 flex items-center gap-3">
                        <a href="{{ route('items.show', $item) }}" class="flex-1 text-center px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-full text-sm text-slate-600 dark:text-slate-300">Cancel</a>
                        <button type="submit" class="flex-1 text-center px-4 py-3 bg-teal-600 text-white font-semibold rounded-full text-sm shadow-sm">
                            Save Changes
                        </button>
                    </div>
                    <div class="sm:hidden h-16"></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
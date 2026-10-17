<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Report Lost or Found Item') }}
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

                <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6"
                      x-data="{ selectedType: '{{ old('type', 'lost') }}' }">
                    @csrf

                    <!-- Incident Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Report Type *</label>
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <label class="flex items-center p-3 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <input type="radio" name="type" value="lost" {{ old('type', 'lost') === 'lost' ? 'checked' : '' }} @change="selectedType = 'lost'" class="text-rose-600 focus:ring-rose-500">
                                <span class="ml-2 flex items-center gap-1.5 font-semibold text-rose-600 dark:text-rose-400">
                                    <svg class="h-3 w-3 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                    I Lost An Item
                                </span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <input type="radio" name="type" value="found" {{ old('type') === 'found' ? 'checked' : '' }} @change="selectedType = 'found'" class="text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 flex items-center gap-1.5 font-semibold text-emerald-600 dark:text-emerald-400">
                                    <svg class="h-3 w-3 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                    I Found An Item
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Category & Title -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Category *</label>
                            <select name="category_id" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="">Select a Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Item Title *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Black Leather Fossil Wallet" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Detailed Description *</label>
                        <textarea name="description" rows="4" required placeholder="Describe appearance, distinctive scratches, brand, contents, etc." class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">{{ old('description') }}</textarea>
                    </div>

                    <!-- Date & Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Date Incident Occurred *</label>
                            <input type="date" name="date_lost_or_found" value="{{ old('date_lost_or_found', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location / Landmark *</label>
                            <input type="text" name="location_name" value="{{ old('location_name') }}" required placeholder="e.g. Central Library 2nd Floor, Desk 12" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                    </div>


                    <!-- Photos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Primary Photo</label>
                            <input type="file" name="primary_image" accept="image/*" class="mt-1 w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-teal-50 file:text-teal-700 dark:file:bg-teal-900/40 dark:file:text-teal-300 hover:file:bg-teal-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Additional Photos (Up to 5)</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="mt-1 w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-teal-50 file:text-teal-700 dark:file:bg-teal-900/40 dark:file:text-teal-300 hover:file:bg-teal-100">
                        </div>
                    </div>

                    <!-- Ownership Verification Questions — Only shown for Found Items -->
                    <div x-show="selectedType === 'found'" x-transition
                         class="p-5 bg-teal-50/50 dark:bg-slate-900/60 rounded-2xl border border-teal-100 dark:border-teal-900/40 space-y-3"
                         x-data="{
                             questions: {{ json_encode(old('verification_questions', [''])) }},
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
                                    Set questions only the real owner can answer (e.g., <em>&quot;What color is the keychain?&quot;</em>). The owner must answer these when claiming the item.
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
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Reward Offered ($ Optional)</label>
                            <input type="number" step="0.01" name="reward_amount" value="{{ old('reward_amount') }}" placeholder="0.00" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div class="pt-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-teal-600 focus:ring-teal-500">
                                <span class="text-sm text-slate-700 dark:text-slate-300">Post Anonymously (Hides your name and direct contact info)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Direct Contact Details (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Contact Phone (Optional)</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Contact Email (Optional)</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', auth()->user()->email) }}" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                    </div>

                                       <!-- Desktop actions -->
                    <div class="hidden sm:flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('items.index') }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 rounded-full text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-full text-sm shadow-sm hover:bg-teal-700 hover:-translate-y-0.5 hover:shadow-md transition">
                            Publish Report
                        </button>
                    </div>

                    <!-- Mobile sticky actions -->
                    <div class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-700 p-3 flex items-center gap-3">
                        <a href="{{ route('items.index') }}" class="flex-1 text-center px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-full text-sm text-slate-600 dark:text-slate-300">Cancel</a>
                        <button type="submit" class="flex-1 text-center px-4 py-3 bg-teal-600 text-white font-semibold rounded-full text-sm shadow-sm">
                            Publish Report
                        </button>
                    </div>

                    <!-- Spacer so sticky bar doesn't cover content on mobile -->
                    <div class="sm:hidden h-16"></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
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

                <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Incident Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Report Type *</label>
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <label class="flex items-center p-3 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <input type="radio" name="type" value="lost" {{ old('type', 'lost') === 'lost' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                <span class="ml-2 flex items-center gap-1.5 font-semibold text-rose-600 dark:text-rose-400">
                                    <svg class="h-3 w-3 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                    I Lost An Item
                                </span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <input type="radio" name="type" value="found" {{ old('type') === 'found' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
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

                    <!-- Coordinates (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400">Pin the location on the map (optional — click to drop a pin)</label>
                                <button type="button" id="locate-me-btn" class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 bg-teal-50 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300 rounded-full hover:bg-teal-100 dark:hover:bg-teal-900/70 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span id="locate-btn-text">Use My Current Location</span>
                                </button>
                            </div>
                            <div id="location-map" style="height: 300px;" class="rounded-2xl border border-slate-300 dark:border-slate-600"></div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1" id="coord-display">No location pinned yet</p>
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

                    <!-- Ownership Verification Questions -->
                    <div class="p-5 bg-teal-50/50 dark:bg-slate-900/60 rounded-2xl border border-teal-100 dark:border-teal-900/40 space-y-3"
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
                                    Ownership Verification Questions (Optional / Recommended for Found Items)
                                </label>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Add questions only the real owner can answer (e.g., <em>&quot;What color is the keychain?&quot;</em>, <em>&quot;What phone wallpaper is set?&quot;</em>). Claimants must answer these before claiming.
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
    @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = {{ old('latitude', 6.5244) }};
        const defaultLng = {{ old('longitude', 3.3792) }};
        const hasExisting = {{ old('latitude') ? 'true' : 'false' }};

        const map = L.map('location-map').setView([defaultLat, defaultLng], hasExisting ? 15 : 6);

      L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
    maxZoom: 19,
    subdomains: 'abcd',
}).addTo(map);

        let marker = hasExisting ? L.marker([defaultLat, defaultLng]).addTo(map) : null;

        function setLocation(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            document.getElementById('coord-display').textContent = `Pinned: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
        }

        const locateBtn = document.getElementById('locate-me-btn');
        const locateBtnText = document.getElementById('locate-btn-text');
        if (locateBtn) {
            locateBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }
                locateBtnText.textContent = 'Locating...';
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        map.setView([lat, lng], 16);
                        setLocation(lat, lng);
                        locateBtnText.textContent = 'Use My Current Location';
                    },
                    function (error) {
                        alert('Could not get your location: ' + error.message);
                        locateBtnText.textContent = 'Use My Current Location';
                    }
                );
            });
        }

        map.on('click', function (e) {
            setLocation(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endpush
</x-app-layout>
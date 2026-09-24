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

                <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Type & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Type</label>
                            <select name="type" class="mt-1 w-full rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 text-sm focus:border-teal-500 focus:ring-teal-500">
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

                    <!-- Coordinates -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400">Pin the location on the map (optional — click to update pin)</label>
                            <button type="button" id="locate-me-btn" class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 bg-teal-50 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300 rounded-full hover:bg-teal-100 dark:hover:bg-teal-900/70 transition">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span id="locate-btn-text">Use My Current Location</span>
                            </button>
                        </div>
                        <div id="location-map" style="height: 300px;" class="rounded-2xl border border-slate-300 dark:border-slate-600"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $item->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $item->longitude) }}">
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1" id="coord-display">
                            @if ($item->latitude)
                                Pinned: {{ $item->latitude }}, {{ $item->longitude }}
                            @else
                                No location pinned yet
                            @endif
                        </p>
                    </div>

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
    @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = {{ old('latitude', $item->latitude ?? 6.5244) }};
        const defaultLng = {{ old('longitude', $item->longitude ?? 3.3792) }};
        const hasExisting = {{ (old('latitude') || $item->latitude) ? 'true' : 'false' }};

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

        map.on('click', function (e) {
            setLocation(e.latlng.lat, e.latlng.lng);
        });

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
    });
</script>
@endpush
</x-app-layout>
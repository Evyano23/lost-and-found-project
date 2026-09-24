<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lost & Found Map') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="items-map" style="height: 600px;" class="rounded-lg border border-gray-300 dark:border-gray-700"></div>
            <p class="text-sm text-gray-500 mt-3">Showing {{ $items->count() }} active item(s) with a pinned location. Click a marker for details.</p>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = @json($mapData);

            const map = L.map('items-map').setView([6.5244, 3.3792], 6);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                maxZoom: 19,
                subdomains: 'abcd',
            }).addTo(map);

            const markers = [];

            items.forEach(function (item) {
                const marker = L.marker([item.lat, item.lng]).addTo(map);
                marker.bindPopup(
                    `<strong>${item.title}</strong><br>` +
                    `${item.type.toUpperCase()} · ${item.category}<br>` +
                    `<a href="${item.url}" class="text-indigo-600">View details</a>`
                );
                markers.push(marker);
            });

            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.2));
            }
        });
    </script>
    @endpush
</x-app-layout>

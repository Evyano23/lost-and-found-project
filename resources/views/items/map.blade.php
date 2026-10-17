<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-slate-100 leading-tight">
                    {{ __('Lost & Found Map') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Live geospatial view of reported lost and found items in your community.
                </p>
            </div>
            @auth
                <a href="{{ route('items.create') }}" class="inline-flex items-center px-5 py-2.5 bg-teal-600 rounded-full font-semibold text-sm text-white hover:bg-teal-700 hover:-translate-y-0.5 shadow-sm hover:shadow-md transition">
                    + Report Item
                </a>
            @endauth
        </div>
    </x-slot>

    @php
        $lostCount = $items->filter(fn($i) => $i->type->value === 'lost')->count();
        $foundCount = $items->count() - $lostCount;
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <!-- Controls & Legend Bar -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mr-1">Filter:</span>
                    <button type="button" id="filter-all" class="map-filter-btn active px-3.5 py-1.5 rounded-full text-xs font-semibold bg-teal-600 text-white shadow-sm transition">
                        All Items ({{ $items->count() }})
                    </button>
                    <button type="button" id="filter-lost" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                        Lost Items ({{ $lostCount }})
                    </button>
                    <button type="button" id="filter-found" class="map-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                        Found Items ({{ $foundCount }})
                    </button>
                </div>

                <div class="flex items-center gap-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-rose-500 border border-white shadow-sm"></span>
                        <span>Lost</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-teal-600 border border-white shadow-sm"></span>
                        <span>Found</span>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white dark:bg-slate-800 p-2 sm:p-3 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="relative z-0 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
                    <div id="items-map" style="height: 600px; width: 100%;" class="z-0"></div>
                </div>
            </div>

            <p class="text-xs text-slate-400 dark:text-slate-500 text-center">
                Showing {{ $items->count() }} active item(s) with pinned coordinates. Click on any pin to view details.
            </p>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        .leaflet-popup-content-wrapper {
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            padding: 4px;
        }
        .leaflet-popup-content {
            margin: 12px 14px;
            line-height: 1.4;
        }
        .custom-pin {
            transition: transform 0.15s ease-in-out;
        }
        .custom-pin:hover {
            transform: scale(1.15);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = @json($mapData);

            // Default center fallback (Lagos coordinates)
            const map = L.map('items-map').setView([6.5244, 3.3792], 6);

            // OpenStreetMap tile layer - Free, reliable, no API key required
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            function getMarkerIcon(type) {
                const isLost = type === 'lost';
                const bg = isLost ? '#f43f5e' : '#0d9488';
                const ring = isLost ? 'rgba(244, 63, 94, 0.25)' : 'rgba(13, 148, 136, 0.25)';
                const iconChar = isLost ? '?' : '✓';

                return L.divIcon({
                    className: 'custom-pin',
                    html: `
                        <div style="position: relative; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <span style="position: absolute; width: 34px; height: 34px; border-radius: 9999px; background: ${ring};"></span>
                            <span style="position: relative; width: 24px; height: 24px; border-radius: 9999px; background: ${bg}; border: 2.5px solid #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 11px; font-weight: 800;">
                                ${iconChar}
                            </span>
                        </div>
                    `,
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                    popupAnchor: [0, -18],
                });
            }

            function buildPopupHtml(item) {
                const isLost = item.type === 'lost';
                const badgeBg = isLost ? '#ffe4e6' : '#ccfbf1';
                const badgeText = isLost ? '#be123c' : '#0f766e';
                const typeLabel = isLost ? 'Lost Item' : 'Found Item';

                return `
                    <div style="min-width: 200px; font-family: inherit;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                            <span style="padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: ${badgeBg}; color: ${badgeText};">
                                ${typeLabel}
                            </span>
                            <span style="font-size: 11px; color: #64748b; font-weight: 500;">
                                ${item.category}
                            </span>
                        </div>
                        <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 10px; line-height: 1.3;">
                            ${item.title}
                        </div>
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 8px;">
                            <a href="${item.url}" style="display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: #0d9488; text-decoration: none;">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                `;
            }

            const markersLayer = L.layerGroup().addTo(map);

            function renderMarkers(filter) {
                markersLayer.clearLayers();

                const filtered = filter === 'all' 
                    ? items 
                    : items.filter(it => it.type === filter);

                const bounds = [];

                filtered.forEach(function (item) {
                    const marker = L.marker([item.lat, item.lng], {
                        icon: getMarkerIcon(item.type)
                    });
                    marker.bindPopup(buildPopupHtml(item));
                    markersLayer.addLayer(marker);
                    bounds.push([item.lat, item.lng]);
                });

                if (bounds.length === 1) {
                    map.setView(bounds[0], 14);
                } else if (bounds.length > 1) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            }

            // Initial render
            renderMarkers('all');

            // Filter button wiring
            const buttons = {
                all: document.getElementById('filter-all'),
                lost: document.getElementById('filter-lost'),
                found: document.getElementById('filter-found'),
            };

            const activeClasses = ['bg-teal-600', 'text-white', 'shadow-sm'];
            const inactiveClasses = ['bg-slate-100', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300'];

            function setFilter(type) {
                Object.keys(buttons).forEach(key => {
                    const btn = buttons[key];
                    if (!btn) return;
                    if (key === type) {
                        btn.classList.add(...activeClasses);
                        btn.classList.remove(...inactiveClasses);
                    } else {
                        btn.classList.remove(...activeClasses);
                        btn.classList.add(...inactiveClasses);
                    }
                });
                renderMarkers(type);
            }

            if (buttons.all) buttons.all.addEventListener('click', () => setFilter('all'));
            if (buttons.lost) buttons.lost.addEventListener('click', () => setFilter('lost'));
            if (buttons.found) buttons.found.addEventListener('click', () => setFilter('found'));
        });
    </script>
    @endpush
</x-app-layout>


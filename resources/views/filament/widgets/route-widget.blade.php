<x-filament-widgets::widget>
    @if($vehicleData)
        <div>
            <div class="relative">
                <div wire:ignore x-data="vehicleMap(@js($vehicleData))" class="w-full">
                    <div x-ref="map" style="height: 500px; width: 100%;" class="z-0 rounded-lg border-2 border-border">
                    </div>
                </div>
            </div>
        </div>

        @assets
        <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <style>
            .leaflet-container {
                font-family: var(--font-sans);
            }

            .leaflet-control-zoom {
                border: none !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }

            .leaflet-control-zoom a {
                background: var(--card) !important;
                color: var(--card-foreground) !important;
                border: 1px solid var(--border) !important;
                width: 36px !important;
                height: 36px !important;
                line-height: 36px !important;
                transition: all 0.2s ease !important;
            }

            .dark .leaflet-control-zoom {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
            }

            .leaflet-control-zoom a:hover {
                background: var(--accent) !important;
                color: var(--accent-foreground) !important;
            }

            .leaflet-control-zoom-in {
                border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            }

            .leaflet-control-zoom-out {
                border-radius: 0 0 var(--radius-lg) var(--radius-lg) !important;
            }

            .driver-marker {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                border: 2px solid gainsboro;
                box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 20%, transparent),
                    0 8px 24px rgba(0, 0, 0, 0.3);
                object-fit: cover;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
            }

            .driver-marker:hover {
                transform: scale(1.15) translateY(-2px);
                box-shadow: 0 0 0 6px color-mix(in srgb, var(--primary) 30%, transparent),
                    0 12px 32px rgba(0, 0, 0, 0.4);
            }

            .driver-marker.active {
                border-color: var(--primary);
                animation: pulseActive 2s ease-in-out infinite;
            }

            .driver-marker.inactive {
                opacity: 0.7;
            }

            @keyframes pulseActive {

                0%,
                100% {
                    box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 30%, transparent),
                        0 8px 24px rgba(0, 0, 0, 0.3);
                }

                50% {
                    box-shadow: 0 0 0 8px color-mix(in srgb, var(--primary) 20%, transparent),
                        0 12px 32px rgba(0, 0, 0, 0.4);
                }
            }

            .start-marker {
                width: 32px;
                height: 32px;
                background: #22c55e;
                border: 3px solid white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 4px 12px rgba(34, 197, 94, 0.5);
            }

            .trip-route {
                stroke: var(--primary);
                stroke-width: 4;
                stroke-linecap: round;
                stroke-linejoin: round;
                fill: none;
            }

            .leaflet-popup-content-wrapper {
                background: var(--card) !important;
                color: var(--card-foreground) !important;
                border-radius: var(--radius-lg) !important;
                border: 1px solid var(--border) !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
                padding: 4px !important;
                min-width: 240px !important;
            }

            .dark .leaflet-popup-content-wrapper {
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6) !important;
            }

            .leaflet-popup-tip {
                background: var(--card) !important;
                border: 1px solid var(--border) !important;
            }

            .leaflet-popup-close-button {
                color: var(--muted-foreground) !important;
                font-size: 18px !important;
                transition: color 0.2s;
                padding-top: 8px !important;
                margin-right: 8px !important;
            }

            .leaflet-popup-close-button:hover {
                color: var(--card-foreground) !important;
            }

            .driver-popup {
                font-family: var(--font-sans);
                padding: 8px;
            }

            .driver-popup .avatar {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                margin: 0 auto 10px;
                display: block;
                border: 2px solid var(--primary);
                box-shadow: 0 2px 8px color-mix(in srgb, var(--primary) 40%, transparent);
            }

            .driver-popup h3 {
                margin: 0 0 8px 0;
                font-size: 15px;
                font-weight: 700;
                text-align: center;
                color: var(--card-foreground);
            }

            .driver-popup .info-row {
                display: flex;
                justify-content: space-between;
                padding: 6px 10px;
                font-size: 12px;
                border-radius: var(--radius-md);
                margin-bottom: 3px;
                background: var(--accent);
                transition: background 0.2s;
            }

            .driver-popup .info-row:hover {
                background: var(--muted);
            }

            .driver-popup .label {
                font-weight: 500;
                color: var(--muted-foreground);
            }

            .driver-popup .value {
                font-weight: 600;
                color: var(--card-foreground);
            }

            .driver-popup .status-badge {
                display: inline-block;
                padding: 3px 10px;
                border-radius: var(--radius-lg);
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .driver-popup .status-active {
                background: var(--primary);
                color: var(--primary-foreground);
                box-shadow: 0 2px 8px color-mix(in srgb, var(--primary) 40%, transparent);
            }

            .driver-popup .status-inactive {
                background: var(--muted);
                color: var(--muted-foreground);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .driver-popup hr {
                margin: 8px 0;
                border: none;
                border-top: 1px solid var(--border);
            }

            .leaflet-control-attribution {
                background: color-mix(in srgb, var(--card) 90%, transparent) !important;
                color: var(--muted-foreground) !important;
                backdrop-filter: blur(10px);
                border-radius: var(--radius-md) 0 0 0 !important;
                padding: 4px 8px !important;
                font-size: 11px !important;
                border: 1px solid var(--border) !important;
                border-right: none !important;
                border-bottom: none !important;
            }

            .leaflet-control-attribution a {
                color: var(--primary) !important;
            }
        </style>
        @endassets

        @script
        <script>
            Alpine.data('vehicleMap', (data) => ({
                map: null,
                marker: null,
                startMarker: null,
                routeLine: null,
                currentTileLayer: null,
                currentTheme: null,
                data: data,

                init() {
                    this.currentTheme = localStorage.getItem('theme') ||
                        (document.documentElement.classList.contains('dark') ? 'dark' : 'light');

                    const lat = this.data?.lat || -23.5505;
                    const lng = this.data?.lng || -46.6333;
                    this.map = L.map(this.$refs.map).setView([lat, lng], 15);

                    this.setTileLayer(this.currentTheme);

                    if (this.data) {
                        this.addMarker(this.data);

                        if (this.data.trip) {
                            this.drawRoute(this.data.trip);
                        }
                    }



                    this.watchThemeChanges();
                },

                setTileLayer(theme) {
                    if (this.currentTileLayer) {
                        this.map.removeLayer(this.currentTileLayer);
                    }

                    if (theme === 'dark') {
                        this.currentTileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                            maxZoom: 19
                        });
                    } else {
                        this.currentTileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                            maxZoom: 19
                        });
                    }

                    this.currentTileLayer.addTo(this.map);
                },

                drawRoute(trip) {
                    if (!trip.route || trip.route.length === 0) {
                        return;
                    }

                    const startIcon = L.divIcon({
                        html: '<div class="start-marker"></div>',
                        className: 'custom-start-marker',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                    });

                    this.startMarker = L.marker([trip.start.lat, trip.start.lng], { icon: startIcon })
                        .addTo(this.map)
                        .bindPopup(`
                                        <div class="driver-popup">
                                            <h3>Início da Viagem</h3>
                                            <div class="info-row">
                                                <span class="label">Horário:</span>
                                                <span class="value">${trip.started_at}</span>
                                            </div>
                                        </div>
                                    `);

                    const routeCoordinates = trip.route.map(point => [point.lat, point.lng]);

                    this.routeLine = L.polyline(routeCoordinates, {
                        color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#0aaa7f',
                        weight: 4,
                        opacity: 0.7,
                        smoothFactor: 1
                    }).addTo(this.map);

                    const bounds = L.latLngBounds([
                        [trip.start.lat, trip.start.lng],
                        ...routeCoordinates
                    ]);

                    this.map.fitBounds(bounds, { padding: [50, 50] });
                },

                watchThemeChanges() {
                    window.addEventListener('storage', (e) => {
                        if (e.key === 'theme' && e.newValue !== this.currentTheme) {
                            this.switchTheme(e.newValue);
                        }
                    });

                    const observer = new MutationObserver(() => {
                        const isDark = document.documentElement.classList.contains('dark');
                        const newTheme = isDark ? 'dark' : 'light';
                        if (newTheme !== this.currentTheme) {
                            this.switchTheme(newTheme);
                        }
                    });

                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });

                    setInterval(() => {
                        const storedTheme = localStorage.getItem('theme');
                        const isDark = document.documentElement.classList.contains('dark');
                        const newTheme = storedTheme || (isDark ? 'dark' : 'light');
                        if (newTheme !== this.currentTheme) {
                            this.switchTheme(newTheme);
                        }
                    }, 1000);
                },

                switchTheme(newTheme) {
                    this.currentTheme = newTheme;
                    this.setTileLayer(newTheme);

                    if (this.routeLine) {
                        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#0aaa7f';
                        this.routeLine.setStyle({ color: primaryColor });
                    }

                    if (this.marker && this.marker.getPopup().isOpen()) {
                        const popupContent = this.createPopupContent(this.data);
                        this.marker.setPopupContent(popupContent);
                    }
                },

                addMarker(data) {
                    const isActive = data.vehicle?.ignition_on;
                    const activeClass = isActive ? 'active' : 'inactive';

                    const avatarUrl = data.driver?.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.vehicle.plate);

                    const icon = L.divIcon({
                        html: `<img src="${avatarUrl}" class="driver-marker ${activeClass}" alt="${data.vehicle.plate}">`,
                        className: 'custom-marker',
                        iconSize: [56, 56],
                        iconAnchor: [28, 28],
                        popupAnchor: [0, -28]
                    });

                    this.marker = L.marker([data.lat, data.lng], { icon })
                        .addTo(this.map);

                    const popupContent = this.createPopupContent(data);
                    this.marker.bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'driver-popup'
                    });
                },

                createPopupContent(data) {
                    const ignitionStatus = data.vehicle?.ignition_on ?
                        '<span class="status-badge status-active">Ligado</span>' :
                        '<span class="status-badge status-inactive">Desligado</span>';

                    const driverInfo = data.driver ? `
                                    <img src="${data.driver.avatar}" class="avatar" alt="${data.driver.name}">
                                    <h3>${data.driver.name}</h3>
                                    <div class="info-row">
                                        <span class="label">Telefone:</span>
                                        <span class="value">${data.driver.phone || 'N/A'}</span>
                                    </div>
                                    <hr>
                                ` : `
                                    <h3>${data.vehicle.plate}</h3>
                                    <p class="text-center text-sm text-gray-500 mb-3">Nenhum motorista atribuído</p>
                                    <hr>
                                `;

                    const tripInfo = data.trip ? `
                                    <div class="info-row">
                                        <span class="label">Distância:</span>
                                        <span class="value">${data.trip.stats.distance_km} km</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">Duração:</span>
                                        <span class="value">${data.trip.stats.duration_minutes} min</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">Vel. Máx:</span>
                                        <span class="value">${data.trip.stats.max_speed} km/h</span>
                                    </div>
                                    <hr>
                                ` : '';

                    return `
                                    <div class="driver-popup">
                                        ${driverInfo}
                                        ${tripInfo}
                                        <div class="info-row">
                                            <span class="label">Veículo:</span>
                                            <span class="value">${data.vehicle?.model || 'N/A'}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Placa:</span>
                                            <span class="value">${data.vehicle?.plate || 'N/A'}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Velocidade:</span>
                                            <span class="value">${data.vehicle?.speed || 0} km/h</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Combustível:</span>
                                            <span class="value">${data.vehicle?.fuel_level || 0}%</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Ignição:</span>
                                            <span class="value">${ignitionStatus}</span>
                                        </div>
                                        <hr>
                                        <div class="info-row">
                                            <span class="label">Dispositivo:</span>
                                            <span class="value">${data.device?.serial || 'N/A'}</span>
                                        </div>
                                    </div>
                                `;
                },


            }));
        </script>
        @endscript
    @else
        <div class="text-center p-12 text-gray-500">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 013.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                </path>
            </svg>
            <p class="text-lg font-medium">Veículo não encontrado</p>
            <p class="text-sm mt-1">Não foi possível carregar os dados do veículo</p>
        </div>
    @endif
</x-filament-widgets::widget>
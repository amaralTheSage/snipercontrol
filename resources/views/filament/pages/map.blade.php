<div class="p-0 m-0 w-full h-screen" x-data="{ viewMode: 'map' }">
    <!-- Toggle Button -->
    <div class="absolute top-4 right-4 z-[1000]">
        <div class="flex gap-2 bg-card border border-border rounded-lg p-1 shadow-lg">
            <button 
                @click="viewMode = 'map'"
                :class="viewMode === 'map' ? 'bg-primary text-primary-foreground' : 'bg-transparent text-muted-foreground hover:text-card-foreground'"
                class="px-4 py-2 rounded-md transition-all duration-200 flex items-center gap-2 font-medium"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 013.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </button>
            <button 
                @click="viewMode = 'list'"
                :class="viewMode === 'list' ? 'bg-primary text-primary-foreground' : 'bg-transparent text-muted-foreground hover:text-card-foreground'"
                class="px-4 py-2 rounded-md transition-all duration-200 flex items-center gap-2 font-medium"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Map View -->
    <div x-show="viewMode === 'map'" x-transition class="relative h-full">
        <div wire:ignore x-data="driverMap(@js($drivers))" class="w-full h-full">
            <div x-ref="map" style="height: 100vh; width: 100%;" class="z-0"></div>
        </div>
    </div>

    <!-- List View -->
    <div x-show="viewMode === 'list'" x-transition class="h-screen overflow-y-auto bg-background p-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-foreground">Motoristas e Veículos</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($drivers as $driver)
                <a href="{{ route('filament.dash.resources.vehicles.view', ['record'=> $driver['vehicle']['id']]) }}" wire:navigate class="bg-card border border-border rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <!-- Driver Header -->
                        <div class="p-6 border-b border-border">
                            <div class="flex items-center gap-4">
                                <img 
                                    src="{{ $driver['avatar'] }}" 
                                    alt="{{ $driver['name'] }}" 
                                    class="w-16 h-16 rounded-full border-4 border-primary object-cover"
                                >
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-card-foreground">{{ $driver['name'] }}</h3>
                                    <p class="text-sm text-muted-foreground">{{ $driver['phone'] ?? 'Sem telefone' }}</p>
                                </div>
                                @if($driver['vehicle']['ignition_on'] ?? false)
                                    <div class="relative">
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        @if($driver['vehicle'])
                            <div class="p-6 bg-accent/50">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide">Veículo</h4>
                                    @if($driver['vehicle']['ignition_on'] ?? false)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary text-primary-foreground">
                                      
                                            Em movimento
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-muted text-muted-foreground">
                                            Parado
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-muted-foreground">Modelo:</span>
                                        <span class="text-sm font-semibold text-card-foreground">{{ $driver['vehicle']['model'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-muted-foreground">Placa:</span>
                                        <span class="text-sm font-bold text-primary">{{ $driver['vehicle']['plate'] ?? 'N/A' }}</span>
                                    </div>

                                    @if(isset($driver['vehicle']['speed']) || isset($driver['vehicle']['fuel_level']))
                                        <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-border">
                                            @if(isset($driver['vehicle']['speed']))
                                                <div class="text-center">
                                                    <div class="text-2xl font-bold text-card-foreground">{{ $driver['vehicle']['speed'] ?? 0 }}</div>
                                                    <div class="text-xs text-muted-foreground">km/h</div>
                                                </div>
                                            @endif
                                            @if(isset($driver['vehicle']['fuel_level']))
                                                <div class="text-center">
                                                    <div class="text-2xl font-bold text-card-foreground">{{ $driver['vehicle']['fuel_level'] ?? 0 }}</div>
                                                    <div class="text-xs text-muted-foreground">% combustível</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-6 bg-muted/50 text-center">
                                <svg class="w-12 h-12 mx-auto text-muted-foreground mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm text-muted-foreground">Nenhum veículo atribuído</p>
                            </div>
                        @endif

                        <!-- Device Info -->
                        @if($driver['device'])
                            <div class="px-6 py-4 bg-card border-t border-border">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-muted-foreground flex items-center gap-2">
                             
                                        Dispositivo:
                                    </span>
                                    <span class=" font-medium text-card-foreground capitalize {{ $driver['device']['status'] === 'online' ? 'text-green-400' : 'text-red-400' }}">{{ $driver['device']['status'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            @if(count($drivers) === 0)
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-muted-foreground mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-foreground mb-2">Nenhum motorista encontrado</h3>
                    <p class="text-muted-foreground">Não há motoristas cadastrados no momento.</p>
                </div>
            @endif
        </div>
    </div>

    @assets
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <style>
        /* Base map container */
        .leaflet-container {
            font-family: var(--font-sans);
            border-radius: var(--radius-lg);
        }

        /* Zoom controls - using card colors */
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

        /* Driver markers - using primary color for border */
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

        /* Popup - using card colors (SMALLER SIZE) */
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

        /* Popup content styling (SMALLER) */
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

        /* Attribution */
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
        Alpine.data('driverMap', (drivers) => ({
            map: null,
            markers: [],
            currentTileLayer: null,
            currentTheme: null,

            init() {
                this.currentTheme = localStorage.getItem('theme') ||
                    (document.documentElement.classList.contains('dark') ? 'dark' : 'light');

                this.map = L.map(this.$refs.map, {
                    zoomControl: false
                }).setView([-23.5505, -46.6333], 12);

                L.control.zoom({
                    position: 'bottomright'
                }).addTo(this.map);

                this.setTileLayer(this.currentTheme);
                this.addDrivers(drivers);

                setInterval(() => {
                    this.updateDriverPositions();
                }, 5000);

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

                this.markers.forEach(item => {
                    if (item.marker.getPopup().isOpen()) {
                        const popupContent = this.createPopupContent(item.driver);
                        item.marker.setPopupContent(popupContent);
                    }
                });
            },

            addDrivers(drivers) {
                drivers.forEach(driver => {
                    this.addDriver(driver);
                });
            },

            addDriver(driver) {
                const isActive = driver.vehicle?.ignition_on;
                const activeClass = isActive ? 'active' : 'inactive';

                const icon = L.divIcon({
                    html: `<img src="${driver.avatar}" class="driver-marker ${activeClass}" alt="${driver.name}">`,
                    className: 'custom-marker',
                    iconSize: [56, 56],
                    iconAnchor: [28, 28],
                    popupAnchor: [0, -28]
                });

                const marker = L.marker([driver.lat, driver.lng], { icon })
                    .addTo(this.map);

                const popupContent = this.createPopupContent(driver);
                marker.bindPopup(popupContent, {
                    maxWidth: 300,
                    className: 'driver-popup'
                });

                this.markers.push({
                    id: driver.id,
                    marker: marker,
                    driver: driver
                });
            },

            createPopupContent(driver) {
                const ignitionStatus = driver.vehicle?.ignition_on ?
                    '<span class="status-badge status-active">Ligado</span>' :
                    '<span class="status-badge status-inactive">Desligado</span>';

                return `
                    <div class="driver-popup">
                        <img src="${driver.avatar}" class="avatar" alt="${driver.name}">
                        <h3>${driver.name}</h3>
                        <div class="info-row">
                            <span class="label">Telefone:</span>
                            <span class="value">${driver.phone || 'N/A'}</span>
                        </div>
                        <hr>
                        <div class="info-row">
                            <span class="label">Veículo:</span>
                            <span class="value">${driver.vehicle?.model || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Placa:</span>
                            <span class="value">${driver.vehicle?.plate || 'N/A'}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Ignição:</span>
                            <span class="value">${ignitionStatus}</span>
                        </div>
                        <hr>
                        <div class="info-row">
                            <span class="label">Dispositivo:</span>
                            <span class=" capitalize ${driver.device.status === 'online' ? 'text-green-400' : 'text-red-400'}">${driver.device.status || 'N/A'}</span>
                        </div>
                    </div>
                `;
            },

            updateDriverPositions() {
                this.markers.forEach(item => {
                    const currentLatLng = item.marker.getLatLng();
                    const newLat = currentLatLng.lat + (Math.random() - 0.5) * 0.001;
                    const newLng = currentLatLng.lng + (Math.random() - 0.5) * 0.001;

                    item.marker.setLatLng([newLat, newLng]);
                    item.driver.lat = newLat;
                    item.driver.lng = newLng;

                    const popupContent = this.createPopupContent(item.driver);
                    item.marker.setPopupContent(popupContent);
                });
            }
        }));
    </script>
    @endscript
</div>
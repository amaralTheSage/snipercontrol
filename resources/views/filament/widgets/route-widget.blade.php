<x-filament-widgets::widget wire:poll.9s="refreshData">
    @if($isLoading)   
   <div class="text-gray-500">
                {{-- Show spinner while loading --}}
                <svg class="animate-spin mx-auto h-12 w-12 text-primary-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-lg font-medium">Carregando mapa...</p>
            </div>
    @elseif($this->vehicleData)

        <div class="relative" >
            
            <!-- Trip Selector Sidebar -->
            @if(!$this->vehicleData['warning'])
                <div class="absolute top-2 right-2 z-10 flex gap-2">
                    <!-- Toggle Button -->
                    <button wire:click="toggleSidebar"
                        class="bg-card p-2 rounded-lg shadow-lg hover:bg-background transition-colors">

                        @if ($sidebarOpen)

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>

                        @else
                            <svg class="w-6 h-6 text-gray-700  dark:text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                                </path>
                            </svg>

                        @endif
                    </button>

                    <!-- Sidebar Panel -->
    
        
                    <div
                        class="bg-card rounded-lg shadow-lg overflow-hidden transition-all duration-300 {{ $sidebarOpen ? 'w-80' : 'w-0' }} h-[482px]">
                        <div class="p-4 {{ $sidebarOpen ? '' : 'hidden' }}">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                Histórico de Viagens
                            </h3>

                            <div class="space-y-2 overflow-y-auto" style="max-height: 420px;">
                                @forelse($availableTrips as $trip)
                                    <div wire:click="selectTrip({{ $trip['id'] }})"
                                        class="p-3 rounded-lg cursor-pointer transition-all 
                                                                                                                                                                                                            {{ $selectedTripId === $trip['id'] ? '     bg-primary/20' : 'bg-background hover:bg-primary/10 ' }}">

                                        <div class="flex items-center justify-between mb-2">
                                            <span
                                                class="text-xs font-medium px-2 py-1 rounded-full 
                                                                                                                                                                                                                {{ $trip['is_current'] ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' }}">
                                                {{ $trip['is_current'] ? 'Em Andamento' : 'Finalizada' }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $trip['distance_km'] }} km
                                            </span>
                                        </div>

                                        <div class="text-sm space-y-1">
                                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $trip['started_at'] }}</span>
                                            </div>

                                            @if(!$trip['is_current'] && $trip['ended_at'])
                                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>{{ $trip['ended_at'] }}</span>
                                                </div>

                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Duração: {{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $trip['started_at'])->diffInMinutes(\Carbon\Carbon::createFromFormat('d/m/Y H:i', $trip['ended_at'])) }} min
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <p class="text-sm">Nenhuma viagem encontrada</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Map Container -->
            <div wire:ignore x-data="vehicleMap(@js($this->vehicleData))" class="w-full focus:outline-none ring-0"
                @vehicle-updated.window="console.log('Event received:', $event.detail); updateVehicle($event.detail.vehicleData)"
                @trip-selected.window="updateTrip($event.detail.tripData)">
                <div x-ref="map" style="height: 500px; width: 100%;" class="z-0 rounded-lg border-2 border-border">
                </div>
            </div>
        </div>

        @assets
        <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
       
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
 console.log('Init called with warningData:', this.warningData); // Add this
        console.log('Data:', this.data); // Add this too

                    this.currentTheme = localStorage.getItem('theme') ||
                        (document.documentElement.classList.contains('dark') ? 'dark' : 'light');

                    const lat = this.data?.lat || -23.5505;
                    const lng = this.data?.lng || -46.6333;
                    this.map = L.map(this.$refs.map).setView([lat, lng], 15);

                    this.setTileLayer(this.currentTheme);

                 if (this.data?.warning && this.data.warning.latitude && this.data.warning.longitude) {
            console.log('Adding warning marker at:', this.data.warning.latitude, this.data.warning.longitude);
            this.addWarningMarker(this.data.warning);
        }


                    if (this.data) {
                        this.addMarker(this.data);

                        if (this.data.trip) {
                            this.drawRoute(this.data.trip);
                        }
                    }
                 
                    this.watchThemeChanges();
                },

      updateVehicle(newData) {
    console.log('updateVehicle called with:', newData);
    
    if (!newData) {
        console.log('No data received!');
        return;
    }
    
    // Update the internal data
    this.data = newData;
    
    // Update marker if it exists
    if (this.marker) {
        // Update position
        this.marker.setLatLng([newData.lat, newData.lng]);
        
        // Update marker icon
        const isActive = newData.vehicle?.ignition_on;
        const activeClass = isActive ? 'active' : 'inactive';
        const avatarUrl = newData.driver?.avatar || 
            'https://ui-avatars.com/api/?name=' + encodeURIComponent(newData.vehicle.plate);
        
        const icon = L.divIcon({
            html: `<img src="${avatarUrl}" class="driver-marker ${activeClass}" alt="${newData.vehicle.plate}">`,
            className: 'custom-marker',
            iconSize: [56, 56],
            iconAnchor: [28, 28],
            popupAnchor: [0, -28]
        });
        
        this.marker.setIcon(icon);
        
        // Update popup content
        const popupContent = this.createPopupContent(newData);
        this.marker.setPopupContent(popupContent);

         // UPDATE THE ROUTE LINE DYNAMICALLY
        if (newData.trip && newData.trip.route && newData.trip.route.length > 0) {
            const routeCoordinates = newData.trip.route.map(point => [point.lat, point.lng]);
            
            if (this.routeLine) {
                // Update existing route line
                this.routeLine.setLatLngs(routeCoordinates);
            } else {
                // Create route line if it doesn't exist
                this.routeLine = L.polyline(routeCoordinates, {
                    color: getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#0aaa7f',
                    weight: 4,
                    opacity: 0.7,
                    smoothFactor: 1
                }).addTo(this.map);
            }
        }
        
        console.log('Marker updated to:', newData.lat, newData.lng);
    } else {
        console.log('No marker found to update!');
    }
},

                updateTrip(tripData) {
                    // Clear existing route and start marker
                    if (this.routeLine) {
                        this.map.removeLayer(this.routeLine);
                        this.routeLine = null;
                    }
                    if (this.startMarker) {
                        this.map.removeLayer(this.startMarker);
                        this.startMarker = null;
                    }

                    // Redraw if trip data exists
                    if (tripData && tripData.route && tripData.route.length > 0) {
                        this.drawRoute(tripData);

                        // Update marker position to current location
                        const currentPos = tripData.current;
                        if (this.marker && currentPos) {
                            this.marker.setLatLng([currentPos.lat, currentPos.lng]);
                        }
                    } else {
                        // No route, just center on marker
                        if (this.marker) {
                            this.map.setView(this.marker.getLatLng(), 15);
                        }
                    }
                },

                setTileLayer(theme) {
                    if (this.currentTileLayer) {
                        this.map.removeLayer(this.currentTileLayer);
                    }

                    if (theme === 'dark') {
                        this.currentTileLayer = L.tileLayer(
                            'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                            maxZoom: 19
                        });
                    } else {
                        this.currentTileLayer = L.tileLayer(
                            'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
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

                    this.startMarker = L.marker([trip.start.lat, trip.start.lng], {
                        icon: startIcon
                    })
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
                        color: getComputedStyle(document.documentElement).getPropertyValue('--primary')
                            .trim() || '#0aaa7f',
                        weight: 4,
                        opacity: 0.7,
                        smoothFactor: 1
                    }).addTo(this.map);

                    const bounds = L.latLngBounds([
                        [trip.start.lat, trip.start.lng],
                        ...routeCoordinates
                    ]);

                    this.map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
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
                        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue(
                            '--primary').trim() || '#0aaa7f';
                        this.routeLine.setStyle({
                            color: primaryColor
                        });
                    }

                    if (this.marker && this.marker.getPopup().isOpen()) {
                        const popupContent = this.createPopupContent(this.data);
                        this.marker.setPopupContent(popupContent);
                    }
                },

addWarningMarker(warning) {
    const severityColors = {
        'high': '#ef4444',
        'medium': '#f59e0b',
        'low': '#eab308',
    };
    
    const color = severityColors[warning.severity] || '#6b7280';
    
    const warningIcon = L.divIcon({
        html: `<div class="warning-marker" style="background-color: ${color};"></div>`,
        className: 'custom-warning-marker',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    this.warningMarker = L.marker(
        [parseFloat(warning.latitude), parseFloat(warning.longitude)], 
        { icon: warningIcon }
    )
    .addTo(this.map)
    .bindPopup(this.createWarningPopupContent(warning), {
        maxWidth: 300,
        className: 'warning-popup'
    });
    
    // Auto-open the warning popup
    this.warningMarker.openPopup();
},

createWarningPopupContent(warning) {
    const typeLabels = {
        'route_diversion': 'Desvio de Rota',
        'cargo_theft': 'Roubo de Carga',
        'fuel_theft': 'Roubo de Combustível',
    };
    
    const severityLabels = {
        'high': 'Alta',
        'medium': 'Média',
        'low': 'Baixa',
    };
    
    const severityColors = {
        'high': '#ef4444',
        'medium': '#f59e0b',
        'low': '#eab308',
    };
    
    return `
        <div class="driver-popup">
            <h3>⚠️ ${typeLabels[warning.type] || warning.type}</h3>
            <div class="info-row">
                <span class="label">Gravidade:</span>
                <span class="status-badge" style="background-color: ${severityColors[warning.severity]}20; color: ${severityColors[warning.severity]};">
                    ${severityLabels[warning.severity] || warning.severity}
                </span>
            </div>
            <div class="info-row">
                <span class="label">Data:</span>
                <span class="value">${warning.occurred_at}</span>
            </div>
            ${warning.description ? `
                <hr>
                <div class="info-row">
                    <span class="value">${warning.description}</span>
                </div>
            ` : ''}
        </div>
    `;
},

                addMarker(data) {
                    const isActive = data.vehicle?.ignition_on;
                    const activeClass = isActive ? 'active' : 'inactive';

                    const avatarUrl = data.driver?.avatar || 'https://ui-avatars.com/api/?name=' +
                        encodeURIComponent(data.vehicle.plate);

                    const icon = L.divIcon({
                        html: `<img src="${avatarUrl}" class="driver-marker ${activeClass}" alt="${data.vehicle.plate}">`,
                        className: 'custom-marker',
                        iconSize: [56, 56],
                        iconAnchor: [28, 28],
                        popupAnchor: [0, -28]
                    });

                    this.marker = L.marker([data.lat, data.lng], {
                        icon
                    })
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
                                                                                                                <span class="value">${data.device?.mac_address || 'N/A'}</span>
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
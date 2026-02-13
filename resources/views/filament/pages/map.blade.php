<div class="p-0 m-0 w-full h-screen" x-data="{
    viewMode: 'map',
    showModal: false,
    selectedDriver: null,
    calculateDuration(startedAt, endedAt) {
        if (!endedAt) return 'Em andamento';
        
        const start = new Date(startedAt);
        const end = new Date(endedAt);
        const diff = end - start;
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        
        if (hours > 0) {
            return `${hours}h ${minutes}min`;
        }
        return `${minutes}min`;
    }


}">
    <!-- Toggle Button -->
    <div class="absolute top-4 right-4 z-1000">
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

    <!-- Driver Info Modal -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showModal = false"
         class="fixed inset-0 z-2000 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
         style="display: none;">
        <div @click.stop 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="bg-card border border-border rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Close Button -->
            <div class="sticky top-0 right-0 z-10 flex justify-end p-4">
                <button @click="showModal = false" class="bg-background/80 hover:bg-background text-foreground rounded-full p-2 transition-colors shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Main Content - Two Column Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 pt-0">
                <!-- Left Column - Large Avatar -->
                <div class="md:col-span-1 flex flex-col items-center justify-start">
                    <img 
                        :src="selectedDriver?.avatar" 
                        :alt="selectedDriver?.name"
                        class="w-full aspect-square rounded-lg border-4 border-primary object-cover shadow-xl"
                    >
                    <h2 class="text-2xl font-bold text-card-foreground mt-4 text-center" x-text="selectedDriver?.name"></h2>
                    <p class="text-muted-foreground mt-1 text-center" x-text="selectedDriver?.phone || 'Sem telefone'"></p>
                </div>

                <!-- Right Column - Details -->
                <div class="md:col-span-2 space-y-4">
                    <!-- Vehicle Info -->
                    <template x-if="selectedDriver?.vehicle">
                        <div>
                            <h3 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                </svg>
                                Informações do Veículo
                            </h3>
                            
                            <div class="bg-accent/50 rounded-lg p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-muted-foreground">Modelo:</span>
                                    <span class="text-sm font-semibold text-card-foreground" x-text="selectedDriver?.vehicle?.model || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-muted-foreground">Placa:</span>
                                    <span class="text-sm font-bold text-primary" x-text="selectedDriver?.vehicle?.plate || 'N/A'"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-muted-foreground">Status:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold"
                                          :class="selectedDriver?.vehicle?.ignition_on ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground'"
                                          x-text="selectedDriver?.vehicle?.ignition_on ? 'Em movimento' : 'Parado'"></span>
                                </div>
                                
                                <template x-if="selectedDriver?.vehicle?.current_speed !== undefined || selectedDriver?.vehicle?.fuel_level !== undefined">
                                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-border">
                                        <template x-if="selectedDriver?.vehicle?.current_speed !== undefined">
                                            <div class="text-center bg-background/50 rounded-lg p-3">
                                                <div class="text-2xl font-bold text-card-foreground" x-text="selectedDriver?.vehicle?.current_speed || 0"></div>
                                                <div class="text-xs text-muted-foreground">km/h</div>
                                            </div>
                                        </template>
                                        <template x-if="selectedDriver?.vehicle?.fuel_level !== undefined">
                                            <div class="text-center bg-background/50 rounded-lg p-3">
                                                <div class="text-2xl font-bold text-card-foreground" x-text="selectedDriver?.vehicle?.fuel_level || 0"></div>
                                                <div class="text-xs text-muted-foreground">% combustível</div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Device Info -->
                    <template x-if="selectedDriver?.device">
                        <div>
                            <h3 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Dispositivo
                            </h3>
                            
                            <div class="bg-accent/50 rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-muted-foreground">Status:</span>
                                    <span class="text-sm font-medium capitalize"
                                          :class="selectedDriver?.device?.status === 'online' ? 'text-green-500' : 'text-red-500'"
                                          x-text="selectedDriver?.device?.status || 'N/A'"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Trips Section - Full Width -->
            <div class="px-6 pb-6">
                <h3 class="text-sm font-semibold text-muted-foreground uppercase tracking-wide mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Últimas Viagens
                </h3>

                <template x-if="selectedDriver?.trips && selectedDriver.trips.length > 0">
                    <div class="space-y-3">
                        <template x-for="trip in selectedDriver.trips" :key="trip.id">
                            <div class="bg-accent/50 rounded-lg p-4 hover:bg-accent/70 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-card-foreground" x-text="new Date(trip.started_at).toLocaleString('pt-BR')"></span>
                                        </div>
                                        
                                        <div class="flex items-center gap-4 text-xs text-muted-foreground">
                                            <template x-if="trip.ended_at">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Duração: </span>
                                                    <span class="font-medium text-card-foreground" x-text="calculateDuration(trip.started_at, trip.ended_at)"></span>
                                                </div>
                                            </template>
                                            
                                            <template x-if="trip.distance_km">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                    </svg>
                                                    <span x-text="trip.distance_km.toFixed(1) + ' km'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold capitalize"
                                              :class="{
                                                  'bg-green-500/20 text-green-500': trip.status === 'completed',
                                                  'bg-blue-500/20 text-blue-500': trip.status === 'in_progress',
                                                  'bg-red-500/20 text-red-500': trip.status === 'cancelled',
                                                  'bg-muted text-muted-foreground': !['completed', 'in_progress', 'cancelled'].includes(trip.status)
                                              }"
                                              x-text="trip.status === 'completed' ? 'Concluída' : 
                                                     trip.status === 'in_progress' ? 'Em andamento' : 
                                                     trip.status === 'cancelled' ? 'Cancelada' : trip.status">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!selectedDriver?.trips || selectedDriver.trips.length === 0">
                    <div class="bg-muted/50 rounded-lg p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-muted-foreground mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-sm text-muted-foreground">Nenhuma viagem registrada</p>
                    </div>
                </template>
            </div>
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
                <div class="bg-card border border-border rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <!-- Driver Header -->
                        <div class="px-8 py-6 border-b border-border">
                            <div class="flex items-center gap-8">
                                <img 
                                    src="{{ $driver['avatar'] }}" 
                                    alt="{{ $driver['name'] }}" 
                                    class="w-20 h-20 rounded-full border-4 border-primary object-cover cursor-pointer hover:scale-105 transition-transform duration-200 hover:border-primary/80"
                                    @click.prevent="selectedDriver = @js($driver); showModal = true"
                                >
                                <div class="flex-1">
                                    <a href="{{ route('filament.dash.resources.vehicles.view', ['record'=> $driver['vehicle']['id']]) }}" wire:navigate class="text-2xl font-bold text-card-foreground hover:text-primary transition-colors">{{ $driver['name'] }}</a>
                                    <p class="text-l text-muted-foreground">{{ $driver['phone'] ?? 'Sem telefone' }}</p>
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
                            <a href="{{ route('filament.dash.resources.vehicles.view', ['record'=> $driver['vehicle']['id']]) }}" wire:navigate>
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

                                        @if(isset($driver['vehicle']['current_speed']) || isset($driver['vehicle']['fuel_level']))
                                            <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-border">
                                                @if(isset($driver['vehicle']['current_speed']))
                                                    <div class="text-center">
                                                        <div class="text-2xl font-bold text-card-foreground">{{ $driver['vehicle']['current_speed'] ?? 0 }}</div>
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
                            </a>
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
                            <a href="{{ route('filament.dash.resources.vehicles.view', ['record'=> $driver['vehicle']['id']]) }}" wire:navigate>
                                <div class="px-6 py-4 bg-card border-t border-border">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-muted-foreground flex items-center gap-2">
                                            Dispositivo:
                                        </span>
                                        <span class="font-medium text-card-foreground capitalize {{ $driver['device']['status'] === 'online' ? 'text-green-400' : 'text-red-400' }}">{{ $driver['device']['status'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
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
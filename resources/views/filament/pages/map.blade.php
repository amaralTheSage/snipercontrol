<div>
    <div class="relative">
        <div wire:ignore x-data="driverMap(@js($drivers))" class="w-full">
            <div x-ref="map" style="height: 100vh; width: 100%;" class="z-0"></div>
        </div>
    </div>

    @assets
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <style>
        /* Base map container */
        .leaflet-container {
            font-family: var(--font-sans);
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
            border: 1px solid var(--primary);
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
            border-color: var(--muted-foreground);
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

        /* Popup - using card colors */
        .leaflet-popup-content-wrapper {
            background: var(--card) !important;
            color: var(--card-foreground) !important;
            border-radius: var(--radius-lg) !important;
            border: 1px solid var(--border) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
            padding: 8px !important;
            min-width: 280px !important;
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
            font-size: 20px !important;
            padding: 8px 12px !important;
            transition: color 0.2s;
        }

        .leaflet-popup-close-button:hover {
            color: var(--card-foreground) !important;
        }

        /* Popup content styling */
        .driver-popup {
            font-family: var(--font-sans);
        }

        .driver-popup .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 16px;
            display: block;
            border: 3px solid var(--primary);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--primary) 40%, transparent);
        }

        .driver-popup h3 {
            margin: 0 0 12px 0;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            color: var(--card-foreground);
        }

        .driver-popup .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: var(--radius-md);
            margin-bottom: 4px;
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
            padding: 4px 12px;
            border-radius: var(--radius-lg);
            font-size: 12px;
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
            margin: 12px 0;
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
                // Get current theme from localStorage or document
                this.currentTheme = localStorage.getItem('theme') ||
                    (document.documentElement.classList.contains('dark') ? 'dark' : 'light');

                // Initialize map
                this.map = L.map(this.$refs.map).setView([-23.5505, -46.6333], 12);

                // Add appropriate tile layer based on theme
                this.setTileLayer(this.currentTheme);

                // Add drivers
                this.addDrivers(drivers);

                // Update positions
                setInterval(() => {
                    this.updateDriverPositions();
                }, 5000);

                // Listen for theme changes
                this.watchThemeChanges();
            },

            setTileLayer(theme) {
                // Remove existing tile layer if any
                if (this.currentTileLayer) {
                    this.map.removeLayer(this.currentTileLayer);
                }

                // Add appropriate tile layer
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
                // Watch for localStorage changes
                window.addEventListener('storage', (e) => {
                    if (e.key === 'theme' && e.newValue !== this.currentTheme) {
                        this.switchTheme(e.newValue);
                    }
                });

                // Watch for class changes on html element
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

                // Also poll localStorage
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
                // Update current theme
                this.currentTheme = newTheme;

                // Switch tile layer
                this.setTileLayer(newTheme);

                // Update all popups if they're open
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
                            <span class="value">${driver.device?.serial || 'N/A'}</span>
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
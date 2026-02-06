<div x-data="livestreamPlayer()" class="w-full">
    <!-- Livestream Player Modal -->


    <div x-show="showPlayer" x-cloak @close-livestream.window="closePlayer()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-card/90 backdrop-blur-sm p-4"
        style="display: none;">
        <div class="relative w-full max-w-6xl max-h-[95vh] flex flex-col">
            <!-- Close Button -->
            <button @click="closePlayer()"
                class="absolute -top-10 right-0 text-card-foreground hover:text-card-foreground/70 transition-colors z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Video Player Container -->
            <div class="bg-card rounded-lg overflow-hidden shadow-2xl flex flex-col max-h-full">
                <!-- Live Badge -->
                <div class="absolute top-4 left-4 z-10">
                    <span
                        class="inline-flex items-center gap-2 bg-green-600 text-white text-sm font-semibold px-3 py-1.5 rounded-full shadow-lg">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        AO VIVO
                    </span>
                </div>

                <!-- Video Stream -->
                <div class="bg-black rounded-lg overflow-hidden shadow-2xl flex flex-col max-h-full"
                    x-ref="playerWrapper">

                    <div class="flex-shrink-0 relative bg-black flex items-center justify-center overflow-hidden"
                        style="height: 80vh; width: 100%; aspect-ratio: 16/9;">

                        <div x-ref="videoContainer" class="w-full h-full flex items-center justify-center"></div>

                        <button @click="toggleFullscreen()"
                            class="absolute bottom-4 right-4 z-20 bg-black/50 hover:bg-black/80 text-white p-2 rounded-full transition-all">
                            <svg x-show="!isFullscreen" class="w-6 h-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                </path>
                            </svg>
                            <svg x-show="isFullscreen" class="w-6 h-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 9L4 4m0 0h4M4 4v4m11 1l5-5m0 0h-4m4 0v4m-11 7l-5 5m0 0v-4m0 4h4m11-1l5 5m0 0v-4m0 4h-4">
                                </path>
                            </svg>
                        </button>

                        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-card">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-card-foreground animate-spin mx-auto" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <p class="text-card-foreground mt-2 text-sm">Conectando à transmissão...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stream Info -->
                <div class="bg-card text-card-foreground p-4 flex-shrink-0">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold" x-text="streamTitle"></h3>
                        <p class="text-sm text-gray-400 mt-1">
                            <span x-text="streamDescription"></span>
                        </p>
                    </div>


                    <!-- Additional Info -->
                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-400 flex-wrap">
                        <span x-show="driver">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-card-foreground" x-text="driver"></span>
                        </span>
                        <span x-show="vehicle">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z">
                                </path>
                                <path
                                    d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z">
                                </path>
                            </svg>
                            <span class="text-card-foreground" x-text="vehicle"></span>
                        </span>
                        <span x-show="startedAt">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Iniciou: <span class="text-card-foreground" x-text="startedAt"></span>
                        </span>


                    </div>

                    {{-- <!-- Quality Selector (Optional) -->
                    <div class="mt-3 pt-3 border-t border-gray-800">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Qualidade</span>
                            <select
                                class="bg-gray-800 text-white text-xs px-2 py-1 rounded border border-gray-700 focus:outline-none focus:border-gray-600">
                                <option value="auto">Auto</option>
                                <option value="1080p">1080p</option>
                                <option value="720p">720p</option>
                                <option value="480p">480p</option>
                                <option value="360p">360p</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/livekit-client@2.5.9/dist/livekit-client.umd.min.js"></script>
    <script>
        /**
         * Make livestreamPlayer globally available for Alpine: x-data="livestreamPlayer()"
         */
        function livestreamPlayer() {
            return {
                showPlayer: false,
                loading: true,
                streamUrl: '',
                streamTitle: '',
                streamDescription: '',
                driver: '',
                vehicle: '',
                startedAt: '',
                viewers: 0,
                isFullscreen: false,

                // Player state
                room: null,
                deviceId: null,

                init() {
                    // Support both Livewire event and plain browser CustomEvent
                    if (window.Livewire && window.Livewire.on) {
                        window.Livewire.on('open-livestream', (payload) => {
                            this.openStream(payload);
                        });
                    }
                    window.addEventListener('open-livestream', (e) => {
                        this.openStream(e.detail ?? e.detail?.detail ?? e?.detail);
                    });

                    // Watch for modal open to run loading behavior
                    this.$watch('showPlayer', (val) => {
                        if (val) {
                            this.loading = true;
                            this.updateViewers();
                        } else {
                            this.loading = false;
                            // cleanup handled in closePlayer
                        }
                    });
                },

                // join LiveKit viewer (tries to use UMD globals). If LiveKit not present, logs and returns.
                async joinViewer(deviceId) {
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const res = await fetch('/livekit/viewer-token', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ device_id: deviceId })
                        });

                        if (!res.ok) throw new Error('token endpoint error');
                        const { token, url } = await res.json();

                        const LK = window.LivekitClient || window.LiveKitClient;
                        this.room = new LK.Room();

                        // 1. Handle tracks from participants ALREADY in the room
                        this.room.on('trackSubscribed', (track, publication) => {
                            this.handleTrackSubscribed(track);
                        });

                        await this.room.connect(url, token);

                        // 2. Check for participants already publishing when we joined
                        this.room.remoteParticipants.forEach(participant => {
                            participant.trackPublications.forEach(publication => {
                                if (publication.track) this.handleTrackSubscribed(publication.track);
                            });
                        });

                    } catch (err) {
                        console.error('joinViewer error', err);
                        this.loading = false;
                    }
                },

                // Helper to keep logic clean
                handleTrackSubscribed(track) {
                    const el = track.attach();
                    el.classList.add('w-full', 'h-full', 'object-contain');

                    if (track.kind === 'video') {
                        el.muted = true; // Crucial for autoplay
                        if (this.$refs.videoContainer) {
                            this.$refs.videoContainer.innerHTML = '';
                            this.$refs.videoContainer.appendChild(el);
                        }
                    } else {
                        document.body.appendChild(el);
                    }
                    this.loading = false;
                },

                async toggleFullscreen() {
                    const container = this.$refs.playerWrapper;

                    if (!document.fullscreenElement) {
                        try {
                            await container.requestFullscreen();
                            this.isFullscreen = true;

                            // Attempt to rotate to landscape on mobile
                            if (screen.orientation && screen.orientation.lock) {
                                await screen.orientation.lock('landscape').catch(e => console.log('Rotation lock blocked or unsupported'));
                            }
                        } catch (err) {
                            console.error(`Error attempting to enable full-screen mode: ${err.message}`);
                        }
                    } else {
                        await document.exitFullscreen();
                        this.isFullscreen = false;

                        if (screen.orientation && screen.orientation.unlock) {
                            screen.orientation.unlock();
                        }
                    }
                },

                // openStream accepts different shapes (Livewire sends array, custom event might send object)
                openStream(data) {
                    if (Array.isArray(data)) data = data[0];
                    if (data?.detail && Array.isArray(data.detail)) data = data.detail[0]; // double-wrapped cases

                    this.streamUrl = data?.url ?? '';
                    this.streamTitle = data?.title ?? 'Transmissão ao Vivo';
                    this.streamDescription = data?.description ?? 'Câmera veicular em tempo real';
                    this.driver = data?.driver ?? '';
                    this.vehicle = data?.vehicle ?? '';
                    this.startedAt = data?.startedAt ?? '';
                    this.viewers = data?.viewers ?? Math.floor(Math.random() * 50) + 10;
                    this.deviceId = data?.device_id ?? data?.deviceId ?? null;

                    this.showPlayer = true;

                    // If a deviceId is provided, try to use LiveKit; otherwise fallback to HLS <video> if you kept it
                    this.$nextTick(async () => {
                        // if you have a videoContainer ref for livekit tracks
                        if (this.deviceId) {
                            await this.joinViewer(this.deviceId);
                        } else if (this.$refs.livestreamPlayer && this.streamUrl) {
                            // fallback HLS/video behavior
                            try {
                                this.$refs.livestreamPlayer.src = this.streamUrl;
                                await this.$refs.livestreamPlayer.load();
                                setTimeout(() => {
                                    this.loading = false;
                                    this.$refs.livestreamPlayer.play().catch(() => {/* autoplay blocked */ });
                                }, 700);
                            } catch (e) {
                                this.loading = false;
                            }
                        } else {
                            this.loading = false;
                        }
                    });
                },

                closePlayer() {
                    if (document.fullscreenElement) document.exitFullscreen();
                    this.showPlayer = false;

                    if (this.room) {
                        try { this.room.disconnect(); } catch (e) {/* ignore */ }
                        this.room = null;
                    }

                    if (this.$refs.livestreamPlayer) {
                        try { this.$refs.livestreamPlayer.pause(); this.$refs.livestreamPlayer.currentTime = 0; } catch (e) { }
                    }
                    if (this.$refs.videoContainer) {
                        this.$refs.videoContainer.innerHTML = '';
                    }
                },

                updateViewers() {
                    if (!this.showPlayer) return;
                    this.viewers += Math.floor(Math.random() * 3) - 1;
                    if (this.viewers < 1) this.viewers = 1;
                    setTimeout(() => this.updateViewers(), 5000);
                }
            };
        }

        // expose globally for Alpine
        window.livestreamPlayer = livestreamPlayer;
    </script>
    <style>
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* When the element is in fullscreen, force it to fill everything */
        div[x-ref="playerWrapper"]:fullscreen {
            width: 100vw !important;
            height: 100vh !important;
            max-height: 100vh !important;
            background-color: black;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        div[x-ref="playerWrapper"]:fullscreen .flex-shrink-0 {
            height: 100vh !important;
            /* Override the 80vh */
            width: 100vw !important;
        }
    </style>
</div>
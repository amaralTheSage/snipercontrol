@php
    $videos = $getState() ?? [];
@endphp

@if(count($videos) > 0)
    <div x-data="videoCarousel()" class="w-full">
  <!-- Video Player Modal -->
        <div x-show="showPlayer" 
             x-cloak
             @click.away="closePlayer()"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
             style="display: none;">
            <div class="relative w-full max-w-5xl max-h-[95vh] flex flex-col">
                <!-- Close Button -->
                <button @click="closePlayer()" 
                        class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Video Player Container -->
                <div class="bg-black rounded-lg overflow-hidden shadow-2xl flex flex-col max-h-full">
                    <!-- Video -->
                    <div class="flex-shrink-0">
                        <video x-ref="videoPlayer"
                               controls
                               class="w-full"
                               style="max-height: 70vh;">
                            <source :src="currentVideo?.url" >
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- Video Info -->
                    <div class="bg-card text-white p-4 flex-shrink-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold" x-text="currentVideo?.title"></h3>
                                <p class="text-sm text-gray-400 mt-1" x-text="currentVideo?.date"></p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-400">Duração</div>
                                <div class="text-lg font-medium" x-text="currentVideo?.duration"></div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-4 text-sm text-gray-400 flex-wrap">
                            <span x-show="currentVideo?.driver">
                                Motorista: <span class="text-white" x-text="currentVideo?.driver"></span>
                            </span>
                            <span x-show="currentVideo?.vehicle">
                                Veículo: <span class="text-white" x-text="currentVideo?.vehicle"></span>
                            </span>
                            <span x-show="currentVideo?.size">
                                <span x-text="currentVideo?.size"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Featured Video (First/Current) -->
        @if(count($videos) > 0)
            <div @click="playVideo(0)" class="cursor-pointer group mb-4">
                <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden shadow-lg transition-all duration-300 group-hover:shadow-2xl group-hover:scale-[1.02]">
                    @if($videos[0]['thumbnail_url'])
                        <img src="{{ $videos[0]['thumbnail_url'] }}" 
                             alt="{{ $videos[0]['title'] }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                            <svg class="w-24 h-24 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Text Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
                        <h4 class="text-white font-semibold text-lg leading-tight">
                            {{ $videos[0]['title'] }}
                        </h4>
                        <p class="text-white/90 text-sm mt-2">
                            {{ $videos[0]['date'] }}
                        </p>
                    </div>

                    <!-- Play Overlay -->
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-white/90 rounded-full p-5">
                            <svg class="w-12 h-12 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Duration Badge -->
                    @if($videos[0]['duration'])
                        <div class="absolute top-3 right-3 bg-black/80 text-white text-sm px-3 py-1.5 rounded">
                            {{ $videos[0]['duration'] }}
                        </div>
                    @endif

                    <!-- Status Badge -->
                    @if($videos[0]['status'] !== 'ready')
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center gap-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                @if($videos[0]['status'] === 'processing')
                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processando
                                @elseif($videos[0]['status'] === 'failed')
                                    Falhou
                                @else
                                    Enviando
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Scrollable Thumbnails Row -->
        @if(count($videos) > 1)
            <div class="relative">
                <div class="overflow-x-auto scrollbar-hide">
                    <div class="flex gap-4">
                        @foreach(array_slice($videos, 1) as $index => $video)
                            @php $actualIndex = $index + 1; @endphp
                            <div @click="playVideo({{ $actualIndex }})" class="flex-shrink-0 w-52 cursor-pointer group">
                                <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden shadow-lg transition-all duration-300 group-hover:shadow-2xl group-hover:scale-105">
                                    @if($video['thumbnail_url'])
                                        <img src="{{ $video['thumbnail_url'] }}" 
                                             alt="{{ $video['title'] }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                            <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Text Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-4">
                                        <h4 class="text-white font-medium text-sm leading-tight">
                                            {{ $video['title'] }}
                                        </h4>
                                        <p class="text-white/80 text-xs mt-1">
                                            {{ $video['date'] }}
                                        </p>
                                    </div>

                                    <!-- Play Overlay -->
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-white/90 rounded-full p-4">
                                            <svg class="w-8 h-8 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Duration Badge -->
                                    @if($video['duration'] && $video['duration']!=='Unknown')
                                        <div class="absolute top-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                                            {{ $video['duration'] }}
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    @if($video['status'] !== 'ready')
                                        <div class="absolute top-2 left-2">
                                            <span class="inline-flex items-center gap-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                                @if($video['status'] === 'processing')
                                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processando
                                                @elseif($video['status'] === 'failed')
                                                    Falhou
                                                @else
                                                    Enviando
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Scroll Gradient Indicators -->
                {{-- @if(count($videos) > 4)
                    <div class="absolute top-0 bottom-0 left-0 w-12 bg-gradient-to-r from-white dark:from-gray-800 to-transparent pointer-events-none"></div>
                    <div class="absolute top-0 bottom-0 right-0 w-12 bg-gradient-to-l from-white dark:from-gray-800 to-transparent pointer-events-none"></div>
                @endif --}}
            </div>
        @endif

        <style>
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>

        <script>
            function videoCarousel() {
                return {
                    showPlayer: false,
                    currentVideo: null,
                    videos: @json($videos),

                    playVideo(index) {
                        if (this.videos[index].status !== 'ready') {
                            return;
                        }

                        this.currentVideo = this.videos[index];
                        this.showPlayer = true;

                        this.$nextTick(() => {
                            if (this.$refs.videoPlayer) {
                                this.$refs.videoPlayer.load();
                                this.$refs.videoPlayer.play();
                            }
                        });
                    },

                    closePlayer() {
                        this.showPlayer = false;
                        if (this.$refs.videoPlayer) {
                            this.$refs.videoPlayer.pause();
                            this.$refs.videoPlayer.currentTime = 0;
                        }
                    }
                }
            }
        </script>
    </div>
@else
    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
        <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
        </svg>
        <p class="text-sm">Nenhuma gravação de vídeo disponível</p>
    </div>
@endif
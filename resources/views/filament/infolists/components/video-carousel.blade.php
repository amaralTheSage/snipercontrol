@php
    $videos = $getState() ?? [];
@endphp

@include('filament.infolists.components.livestream-player')


<div x-data="videoCarousel()" class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Video Player -->
        <div class="lg:col-span-3">
            <div class="bg-card rounded-lg overflow-hidden shadow-lg sticky top-4">
                @if(count($videos) > 0)
                    <!-- Video Player -->
                    <div class="relative aspect-video bg-card">
                        <video x-ref="videoPlayer" x-show="currentVideo" controls class="w-full h-full">
                            <source :src="currentVideo?.url">
                            Your browser does not support the video tag.
                        </video>

                        <!-- Placeholder when no video selected -->
                        <div x-show="!currentVideo" class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="mx-auto w-24 h-24 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                    </path>
                                </svg>
                                <p class="text-sm">Selecione um vídeo na linha do tempo</p>
                            </div>
                        </div>
                    </div>


                    <!-- Video Info -->
                    <div x-show="currentVideo" class="bg-card text-card-foreground p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold" x-text="currentVideo?.title"></h3>
                                <p class="text-sm text-gray-400 mt-1" x-text="currentVideo?.date"></p>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-sm text-gray-400">Duração</div>
                                <div class="text-lg font-medium text-card-foreground" x-text="currentVideo?.duration"></div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-4 text-sm text-gray-400 flex-wrap">
                            <span x-show="currentVideo?.driver">
                                Motorista: <span class="text-card-foreground" x-text="currentVideo?.driver"></span>
                            </span>
                            <span x-show="currentVideo?.vehicle">
                                Veículo: <span class="text-card-foreground" x-text="currentVideo?.vehicle"></span>
                            </span>
                            <span x-show="currentVideo?.size">
                                <span x-text="currentVideo?.size"></span>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="aspect-video flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-sm">Nenhuma gravação de vídeo disponível</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Calendar & Timelines -->
        <div class="lg:col-span-1 space-y-4 ">
            <!-- Calendar -->
            <div class="bg-card rounded-lg shadow-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <button @click="previousMonth()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <h3 class="text-lg font-semibold" x-text="currentMonthYear"></h3>
                    <button @click="nextMonth()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <template x-for="day in ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB']">
                        <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2"
                            x-text="day"></div>
                    </template>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-1">
                    <template x-for="day in calendarDays" :key="day.date">
                        <button @click="selectDate(day.date)" :class="{
                                'bg-primary-600 text-white': day.isSelected,
                                'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600': !day.isSelected && day.hasEvents,
                                'hover:bg-gray-50 dark:hover:bg-gray-700': !day.isSelected && !day.hasEvents,
                                'opacity-50': !day.isCurrentMonth
                            }"
                            class="aspect-square rounded-lg text-sm font-medium transition-colors relative p-1 flex items-center justify-center">
                            <span x-text="day.dayNumber"></span>
                            <span x-show="day.hasEvents && !day.isSelected"
                                class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary-600 rounded-full"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Timelines -->
            <div class="bg-card rounded-lg shadow-lg p-4 space-y-1">
                <!-- Zoom Controls -->
                <div class="flex justify-end items-center gap-2">
                    <button @click="zoomOut()" :disabled="zoomLevel <= 1"
                        :class="{'opacity-50 cursor-not-allowed': zoomLevel <= 1}"
                        class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        -
                    </button>
                    <span class="text-xs text-gray-500 w-8 text-center" x-text="zoomLevel + 'x'"></span>
                    <button @click="zoomIn()" :disabled="zoomLevel >= 4"
                        :class="{'opacity-50 cursor-not-allowed': zoomLevel >= 4}"
                        class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        +
                    </button>
                </div>

                <!-- Video Timeline -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                </path>
                            </svg>
                            <span class="text-sm font-semibold">Vídeo</span>
                        </div>
                        <span class="text-xs text-gray-500"
                            x-text="filteredVideos.length + ' evento' + (filteredVideos.length !== 1 ? 's' : '')"></span>
                    </div>

                    <!-- Timeline Container (scrollable) -->
                    <div class="overflow-x-auto scrollbar-thin" x-ref="timelineContainer">
                        <div class="relative h-10 bg-card dark:bg-gray-700 rounded-lg overflow-hidden "
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <!-- Hour markers -->
                            <div class="absolute inset-0 flex">
                                <template x-for="hour in 24" :key="hour">
                                    <div class="flex-1 border-r border-gray-200 dark:border-gray-600 relative">
                                        <span x-show="
        (zoomLevel === 2 && (hour - 1) % 2 === 0) ||
        (zoomLevel >= 3)
    " class="absolute top-0 left-1 text-[10px] text-gray-400" x-text="String(hour - 1).padStart(2, '0') + 'h'">
                                        </span>

                                    </div>
                                </template>
                            </div>

                            <!-- Video segments -->
                            <template x-for="(video, index) in filteredVideos" :key="video.id">
                                <div @click="playVideo(video)"
                                    :style="`left: ${video.timelineStart}%; width: ${video.timelineWidth}%`" :class="{
                                        'bg-green-500 hover:bg-green-600': video.status === 'ready',
                                        'bg-yellow-500 hover:bg-yellow-600': video.status === 'processing',
                                        'bg-red-500 hover:bg-red-600': video.status === 'failed',
                                        'cursor-pointer': video.status === 'ready',
                                        'cursor-not-allowed': video.status !== 'ready'
                                    }" class="absolute top-1 bottom-1 rounded transition-all group"
                                    :title="video.title + ' - ' + video.timeRange">
                                    <!-- Tooltip on hover -->
                                    <div
                                        class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-card text-white text-xs rounded whitespace-nowrap z-10">
                                        <div class="font-semibold" x-text="video.timeRange"></div>
                                        <div x-text="video.duration"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty state overlay -->
                            <div x-show="filteredVideos.length === 0"
                                class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs">
                                Nenhum vídeo neste dia
                            </div>
                        </div>
                    </div>

                    <!-- Time labels -->
                    <div x-show="zoomLevel === 1" class="overflow-x-auto scrollbar-thin mb-4" x-ref="timelineLabels">
                        <div class="flex justify-between mt-1 text-xs text-gray-500"
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <span>00h</span>
                            <span x-show="zoomLevel >= 2">04h</span>
                            <span>06h</span>
                            <span x-show="zoomLevel >= 2">08h</span>
                            <span x-show="zoomLevel >= 3">10h</span>
                            <span>12h</span>
                            <span x-show="zoomLevel >= 3">14h</span>
                            <span x-show="zoomLevel >= 2">16h</span>
                            <span>18h</span>
                            <span x-show="zoomLevel >= 2">20h</span>
                            <span x-show="zoomLevel >= 3">22h</span>
                            <span>24h</span>
                        </div>
                    </div>
                </div>

                <!-- Microphone Timeline (placeholder for future) -->
                <div class="opacity-50">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-semibold">Microfone</span>
                        </div>
                        <span class="text-xs text-gray-500">0 eventos</span>
                    </div>

                    <div class="overflow-x-auto scrollbar-thin">
                        <div class="relative h-10 bg-card dark:bg-gray-700 rounded-lg overflow-hidden"
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <div class="absolute inset-0 flex">
                                <template x-for="hour in 24" :key="hour">
                                    <div class="flex-1 border-r border-gray-200 dark:border-gray-600"></div>
                                </template>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs">
                                Nenhum áudio neste dia
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto scrollbar-thin mb-4" x-show="zoomLevel === 1">
                        <div class="flex justify-between mt-1 text-xs text-gray-500"
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <span>00h</span>
                            <span x-show="zoomLevel >= 2">04h</span>
                            <span>06h</span>
                            <span x-show="zoomLevel >= 2">08h</span>
                            <span x-show="zoomLevel >= 3">10h</span>
                            <span>12h</span>
                            <span x-show="zoomLevel >= 3">14h</span>
                            <span x-show="zoomLevel >= 2">16h</span>
                            <span>18h</span>
                            <span x-show="zoomLevel >= 2">20h</span>
                            <span x-show="zoomLevel >= 3">22h</span>
                            <span>24h</span>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex items-center gap-4 text-xs pt-2 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded"></div>
                        <span>Pronto</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-yellow-500 rounded"></div>
                        <span>Processando</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded"></div>
                        <span>Falhou</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar styles */
        .scrollbar-thin::-webkit-scrollbar {
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        /* Sync scroll */
        .scrollbar-thin {
            scrollbar-width: thin;
        }
    </style>

    <script>
        function videoCarousel() {
            return {
                videos: @json($videos),
                currentVideo: null,
                selectedDate: new Date().toISOString().split('T')[0],
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                zoomLevel: 1, // 1x, 2x, 3x, 4x

                get currentMonthYear() {
                    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                    return `${months[this.currentMonth]} ${this.currentYear}`;
                },

                get calendarDays() {
                    const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                    const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                    const prevLastDay = new Date(this.currentYear, this.currentMonth, 0);

                    const days = [];
                    const firstDayOfWeek = firstDay.getDay();

                    // Previous month days
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        const date = new Date(this.currentYear, this.currentMonth - 1, prevLastDay.getDate() - i);
                        days.push(this.createDayObject(date, false));
                    }

                    // Current month days
                    for (let i = 1; i <= lastDay.getDate(); i++) {
                        const date = new Date(this.currentYear, this.currentMonth, i);
                        days.push(this.createDayObject(date, true));
                    }

                    // Next month days
                    const remainingDays = 42 - days.length;
                    for (let i = 1; i <= remainingDays; i++) {
                        const date = new Date(this.currentYear, this.currentMonth + 1, i);
                        days.push(this.createDayObject(date, false));
                    }

                    return days;
                },

                createDayObject(date, isCurrentMonth) {
                    const dateStr = date.toISOString().split('T')[0];
                    const hasEvents = this.videos.some(v => v.date.startsWith(dateStr.split('-').reverse().join('/')));

                    return {
                        date: dateStr,
                        dayNumber: date.getDate(),
                        isCurrentMonth,
                        hasEvents,
                        isSelected: dateStr === this.selectedDate
                    };
                },

                get filteredVideos() {
                    const selectedDateStr = this.selectedDate.split('-').reverse().join('/');
                    return this.videos
                        .filter(v => v.date.startsWith(selectedDateStr))
                        .map(video => {
                            const timeParts = video.date.split(' ')[1]?.split(':') || ['00', '00'];
                            const hour = parseInt(timeParts[0]);
                            const minute = parseInt(timeParts[1]);

                            const startMinutes = hour * 60 + minute;
                            const durationParts = video.duration?.split(':') || ['00', '00', '00'];
                            const durationMinutes = parseInt(durationParts[0]) * 60 + parseInt(durationParts[1]);

                            // Calculate position and width with zoom
                            const timelineStart = (startMinutes / (24 * 60)) * 100;
                            const calculatedWidth = (durationMinutes / (24 * 60)) * 100;

                            // Minimum width: 1% at zoom 1x, scales with zoom
                            const minWidth = 1 / this.zoomLevel;
                            const timelineWidth = Math.max(calculatedWidth, minWidth);

                            return {
                                ...video,
                                timelineStart,
                                timelineWidth,
                                timeRange: `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')} - ${String(Math.floor((startMinutes + durationMinutes) / 60) % 24).padStart(2, '0')}:${String((startMinutes + durationMinutes) % 60).padStart(2, '0')}`
                            };
                        });
                },

                selectDate(date) {
                    this.selectedDate = date;
                    this.currentVideo = null;
                },

                previousMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },

                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },

                zoomIn() {
                    if (this.zoomLevel < 4) {
                        this.zoomLevel++;
                        this.syncScroll();
                    }
                },

                zoomOut() {
                    if (this.zoomLevel > 1) {
                        this.zoomLevel--;
                        this.syncScroll();
                    }
                },

                syncScroll() {
                    // Sync scroll between timeline and labels
                    this.$nextTick(() => {
                        if (this.$refs.timelineContainer && this.$refs.timelineLabels) {
                            this.$refs.timelineLabels.scrollLeft = this.$refs.timelineContainer.scrollLeft;
                        }
                    });
                },

                playVideo(video) {
                    if (video.status !== 'ready') {
                        return;
                    }

                    this.currentVideo = video;

                    this.$nextTick(() => {
                        if (this.$refs.videoPlayer) {
                            this.$refs.videoPlayer.load();
                            this.$refs.videoPlayer.play();
                        }
                    });
                },

                init() {
                    // Sync scroll between timeline container and labels
                    this.$watch('$refs.timelineContainer', (container) => {
                        if (container) {
                            container.addEventListener('scroll', () => {
                                if (this.$refs.timelineLabels) {
                                    this.$refs.timelineLabels.scrollLeft = container.scrollLeft;
                                }
                            });
                        }
                    });
                }
            }
        }
    </script>
</div>
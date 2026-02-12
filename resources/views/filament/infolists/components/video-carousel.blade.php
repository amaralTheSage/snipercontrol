@php
    $state = $getState() ?? [];
    $videos = $state['videos'] ?? [];
    $audios = $state['audios'] ?? [];
@endphp

@include('filament.infolists.components.livestream-player')

<div x-data="videoCarousel()" class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3">
            <div class="bg-card rounded-lg overflow-hidden shadow-lg sticky top-4">

                <div class="relative aspect-video bg-black">
                    <video x-ref="mediaPlayer" x-show="currentMedia" controls playsinline crossorigin="anonymous"
                        class="w-full h-full z-10 relative">
                    </video>


                    <div x-show="currentMedia?.type === 'audio'"
                        class="absolute inset-0 flex items-center justify-center bg-gray-900 pointer-events-none z-0">
                        <div class="text-center animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-audio-lines-icon size-48 text-primary lucide-audio-lines">
                                <path d="M2 10v3" />
                                <path d="M6 6v11" />
                                <path d="M10 3v18" />
                                <path d="M14 8v7" />
                                <path d="M18 5v13" />
                                <path d="M22 10v3" />
                            </svg>
                            <p class="text-white mt-4 font-medium text-lg">Reproduzindo Áudio</p>
                        </div>
                    </div>

                    <div x-show="!currentMedia" class="absolute inset-0 flex items-center justify-center bg-card">
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto w-24 h-24 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                                </path>
                            </svg>
                            <p class="text-sm">Selecione um vídeo ou áudio na linha do tempo</p>
                        </div>
                    </div>
                </div>

                <div x-show="currentMedia" class="bg-card text-card-foreground p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span x-show="currentMedia?.type === 'audio'"
                                    class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">Áudio</span>
                                <span x-show="currentMedia?.type === 'video'"
                                    class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Vídeo</span>
                                <h3 class="text-lg font-semibold" x-text="currentMedia?.title"></h3>
                            </div>
                            <p class="text-sm text-gray-400 mt-1" x-text="currentMedia?.date"></p>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-sm text-gray-400">Duração</div>
                            <div class="text-lg font-medium text-card-foreground" x-text="currentMedia?.duration"></div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-400 flex-wrap">
                        <span x-show="currentMedia?.driver">
                            Motorista: <span class="text-card-foreground" x-text="currentMedia?.driver"></span>
                        </span>
                        <span x-show="currentMedia?.vehicle">
                            Veículo: <span class="text-card-foreground" x-text="currentMedia?.vehicle"></span>
                        </span>
                        <span x-show="currentMedia?.size">
                            <span x-text="currentMedia?.size"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-4">
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
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <template x-for="day in ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB']">
                        <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2"
                            x-text="day"></div>
                    </template>
                </div>
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
                            <div class="absolute bottom-1 flex gap-0.5">
                                <span x-show="day.hasVideos && !day.isSelected"
                                    class="w-1 h-1 bg-green-500 rounded-full"></span>
                                <span x-show="day.hasAudios && !day.isSelected"
                                    class="w-1 h-1 bg-blue-500 rounded-full"></span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-card rounded-lg shadow-lg p-4 space-y-1">
                <div class="flex justify-end items-center gap-2">
                    <button @click="zoomOut()" :disabled="zoomLevel <= 1"
                        :class="{'opacity-50 cursor-not-allowed': zoomLevel <= 1}"
                        class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">-</button>
                    <span class="text-xs text-gray-500 w-8 text-center" x-text="zoomLevel + 'x'"></span>
                    <button @click="zoomIn()" :disabled="zoomLevel >= 4"
                        :class="{'opacity-50 cursor-not-allowed': zoomLevel >= 4}"
                        class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">+</button>
                </div>

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
                        <span class="text-xs text-gray-500" x-text="filteredVideos.length"></span>
                    </div>

                    <div class="overflow-x-auto scrollbar-thin" x-ref="timelineContainer">
                        <div class="relative h-10 bg-card dark:bg-gray-700 rounded-lg overflow-hidden"
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <div class="absolute inset-0 flex">
                                <template x-for="hour in 24" :key="hour">
                                    <div class="flex-1 border-r border-gray-200 dark:border-gray-600 relative">
                                        <span x-show="(zoomLevel === 2 && (hour - 1) % 2 === 0) || (zoomLevel >= 3)"
                                            class="absolute top-0 left-1 text-[10px] text-gray-400"
                                            x-text="String(hour - 1).padStart(2, '0') + 'h'"></span>
                                    </div>
                                </template>
                            </div>

                            <template x-for="video in filteredVideos" :key="video.id">
                                <div @click="playMedia(video)"
                                    :style="`left: ${video.timelineStart}%; width: ${video.timelineWidth}%`" :class="{
                                        'bg-green-500 hover:bg-green-600  ': video.status === 'ready',
                                        'bg-yellow-500 hover:bg-yellow-600': video.status === 'processing',
                                        'bg-red-500 hover:bg-red-600': video.status === 'failed',
                                        'cursor-pointer': video.status === 'ready',
                                        'cursor-not-allowed': video.status !== 'ready',
                                        'ring-2 ring-primary z-10': currentMedia?.id === video.id
                                    }" class="absolute top-1 bottom-1 rounded transition-all group"
                                    :title="video.title">

                                    <div
                                        class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black text-white text-xs rounded whitespace-nowrap z-20 shadow-lg">
                                        <div class="font-bold">Vídeo</div>
                                        <div x-text="video.timeRange"></div>
                                        <div x-text="video.duration"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="filteredVideos.length === 0"
                                class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs">Nenhum
                                vídeo</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
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
                        <span class="text-xs text-gray-500" x-text="filteredAudios.length"></span>
                    </div>

                    <div class="overflow-x-auto scrollbar-thin" x-ref="audioTimelineContainer">
                        <div class="relative h-10 bg-card dark:bg-gray-700 rounded-lg overflow-hidden"
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <div class="absolute inset-0 flex">
                                <template x-for="hour in 24" :key="hour">
                                    <div class="flex-1 border-r border-gray-200 dark:border-gray-600"></div>
                                </template>
                            </div>

                            <template x-for="audio in filteredAudios" :key="audio.id">
                                <div @click="playMedia(audio)"
                                    :style="`left: ${audio.timelineStart}%; width: ${audio.timelineWidth}%`" :class="{
                                        'bg-blue-500 hover:bg-blue-600 ': true,
                                        'cursor-pointer': true,
                                        'ring-2 ring-primary z-10': currentMedia?.id === audio.id
                                    }"
                                    class="absolute top-1 bottom-1 rounded transition-all group opacity-80 hover:opacity-100">

                                    <div
                                        class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black text-white text-xs rounded whitespace-nowrap z-20 shadow-lg">
                                        <div class="font-bold">Áudio</div>
                                        <div x-text="audio.timeRange"></div>
                                        <div x-text="audio.duration"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="filteredAudios.length === 0"
                                class="absolute inset-0 flex items-center justify-center text-gray-400 text-xs">Nenhum
                                áudio</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto scrollbar-thin mb-4" x-show="zoomLevel === 1" x-ref="timelineLabels">
                        <div class="flex justify-between mt-1 text-xs text-gray-500"
                            :style="`min-width: ${100 * zoomLevel}%`">
                            <span>00h</span><span x-show="zoomLevel >= 2">04h</span><span>06h</span><span
                                x-show="zoomLevel >= 2">08h</span><span x-show="zoomLevel >= 3">10h</span>
                            <span>12h</span><span x-show="zoomLevel >= 3">14h</span><span
                                x-show="zoomLevel >= 2">16h</span><span>18h</span><span
                                x-show="zoomLevel >= 2">20h</span>
                            <span x-show="zoomLevel >= 3">22h</span><span>24h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
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

        .scrollbar-thin {
            scrollbar-width: thin;
        }
    </style>

    <script>
        function videoCarousel() {
            const pad = n => String(n).padStart(2, '0');
            const toLocalISO = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
            const toBR = d => `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()}`;

            return {
                videos: @json($videos),
                audios: @json($audios),
                currentMedia: null,
                selectedDate: toLocalISO(new Date()),
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                zoomLevel: 1,

                get currentMonthYear() {
                    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                    return `${months[this.currentMonth]} ${this.currentYear}`;
                },

                get calendarDays() {
                    const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                    const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                    const prevLastDay = new Date(this.currentYear, this.currentMonth, 0);
                    const days = [];
                    const firstDayOfWeek = firstDay.getDay();
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        const date = new Date(this.currentYear, this.currentMonth - 1, prevLastDay.getDate() - i);
                        days.push(this.createDayObject(date, false));
                    }
                    for (let i = 1; i <= lastDay.getDate(); i++) {
                        const date = new Date(this.currentYear, this.currentMonth, i);
                        days.push(this.createDayObject(date, true));
                    }
                    const remainingDays = 42 - days.length;
                    for (let i = 1; i <= remainingDays; i++) {
                        const date = new Date(this.currentYear, this.currentMonth + 1, i);
                        days.push(this.createDayObject(date, false));
                    }
                    return days;
                },

                createDayObject(date, isCurrentMonth) {
                    const dateStr = toLocalISO(date);      // YYYY-MM-DD
                    const reverseDateStr = toBR(date);     // DD/MM/YYYY

                    // Debugging helper removed in production; kept trimmed checks
                    const hasVideos = this.videos.some(v => (v.date || '').trim().startsWith(reverseDateStr));
                    const hasAudios = this.audios.some(a => (a.date || '').trim().startsWith(reverseDateStr));

                    return {
                        date: dateStr,
                        dayNumber: date.getDate(),
                        isCurrentMonth,
                        hasEvents: hasVideos || hasAudios,
                        hasVideos,
                        hasAudios,
                        isSelected: dateStr === this.selectedDate
                    };
                },

                processTimelineItems(items) {
                    return items
                        .filter(item => {
                            if (!item || !item.date) return false;
                            const raw = (item.date || '').trim().slice(0, 10); // "DD/MM/YYYY"
                            const parts = raw.split('/');
                            if (parts.length !== 3) return false;
                            const itemISO = `${parts[2]}-${parts[1]}-${parts[0]}`; // YYYY-MM-DD
                            return itemISO === this.selectedDate;
                        })
                        .map(item => {
                            const timeParts = (item.date.split(' ')[1] || '00:00').split(':');
                            const hour = parseInt(timeParts[0]) || 0;
                            const minute = parseInt(timeParts[1]) || 0;
                            const startMinutes = hour * 60 + minute;

                            const durationParts = (item.duration || '00:00').split(':').map(s => parseInt(s) || 0);
                            let durationMinutes = 1;
                            if (durationParts.length === 3) {
                                durationMinutes = durationParts[0] * 60 + durationParts[1];
                            } else if (durationParts.length === 2) {
                                durationMinutes = durationParts[0];
                            } else if (durationParts.length === 1) {
                                durationMinutes = durationParts[0];
                            }
                            if (!Number.isFinite(durationMinutes) || durationMinutes <= 0) durationMinutes = 1;

                            const timelineStart = (startMinutes / (24 * 60)) * 100;
                            const calculatedWidth = (durationMinutes / (24 * 60)) * 100;
                            const minWidth = 2 / this.zoomLevel;
                            const timelineWidth = Math.max(calculatedWidth, minWidth);
                            const endTimeTotal = startMinutes + durationMinutes;

                            return {
                                ...item,
                                timelineStart,
                                timelineWidth,
                                timeRange: `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')} - ${String(Math.floor(endTimeTotal / 60) % 24).padStart(2, '0')}:${String(endTimeTotal % 60).padStart(2, '0')}`
                            };
                        });
                },

                get filteredVideos() { return this.processTimelineItems(this.videos); },

                get filteredAudios() { return this.processTimelineItems(this.audios); },

                selectDate(date) {
                    this.selectedDate = date;
                    this.currentMedia = null;
                },

                previousMonth() {
                    if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; } else { this.currentMonth--; }
                },

                nextMonth() {
                    if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; } else { this.currentMonth++; }
                },

                zoomIn() { if (this.zoomLevel < 4) { this.zoomLevel++; this.syncScroll(); } },

                zoomOut() { if (this.zoomLevel > 1) { this.zoomLevel--; this.syncScroll(); } },

                syncScroll() {
                    this.$nextTick(() => {
                        const scrollLeft = this.$refs.timelineContainer?.scrollLeft || 0;
                        if (this.$refs.timelineLabels) this.$refs.timelineLabels.scrollLeft = scrollLeft;
                        if (this.$refs.audioTimelineContainer) this.$refs.audioTimelineContainer.scrollLeft = scrollLeft;
                    });
                },

                playMedia(media) {
                    if (media.status !== 'ready' && media.type === 'video') return;
                    this.currentMedia = media;
                    this.$nextTick(() => {
                        const player = this.$refs.mediaPlayer;
                        if (!player) return;
                        try { player.pause(); } catch (e) { }
                        player.removeAttribute('src');
                        player.load();
                        player.src = media.url;
                        player.load();
                        player.play().catch(() => { });
                    });
                },

                init() {
                    const containers = [this.$refs.timelineContainer, this.$refs.audioTimelineContainer];
                    containers.forEach(container => {
                        if (container) {
                            container.addEventListener('scroll', (e) => {
                                const left = e.target.scrollLeft;
                                if (this.$refs.timelineContainer && this.$refs.timelineContainer !== e.target) this.$refs.timelineContainer.scrollLeft = left;
                                if (this.$refs.audioTimelineContainer && this.$refs.audioTimelineContainer !== e.target) this.$refs.audioTimelineContainer.scrollLeft = left;
                                if (this.$refs.timelineLabels) this.$refs.timelineLabels.scrollLeft = left;
                            });
                        }
                    });
                }
            };
        }

    </script>
</div>
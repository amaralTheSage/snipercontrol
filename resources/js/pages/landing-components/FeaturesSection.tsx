import { ArrowRight } from 'lucide-react';

export default function FeaturesSection() {
    return (
        <section id="features" className="h-full min-h-screen overflow-hidden">
            <div className="mx-auto px-4 py-8 md:px-6">
                {/* White Card Wrapper */}
                <div className="relative overflow-hidden rounded-3xl bg-white p-6 shadow-xl shadow-slate-200/50 md:rounded-[3rem] md:p-12 lg:px-20 lg:py-16">
                    <div className="relative z-10 grid grid-cols-1 items-center gap-8 md:gap-12 lg:grid-cols-2 lg:gap-16">
                        {/* Left Column - Content */}
                        <div>
                            <h2 className="text-3xl font-bold tracking-tight text-slate-900 md:text-5xl">
                                Transforme a Realidade <br />
                                em{' '}
                                <span className="text-slate-900">
                                    Resultados
                                </span>{' '}
                                <br />
                                de Alto Impacto
                            </h2>

                            <p className="mt-6 text-lg leading-relaxed text-slate-600">
                                Nossa plataforma de telemetria avançada empodera
                                você a desenhar, implantar e analisar cada
                                interação para que sua frota performe
                                perfeitamente em qualquer ambiente.
                            </p>

                            <div className="mt-12 grid grid-cols-1 gap-x-8 gap-y-10 md:grid-cols-2">
                                {/* Feature 1 */}
                                <div className="group">
                                    <div className="mb-3 flex items-center gap-2 text-lg font-bold text-slate-900">
                                        <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                                        <h3>Rastreamento em Tempo Real</h3>
                                    </div>
                                    <p className="text-sm leading-relaxed text-slate-500">
                                        Monitoramento contínuo de localização,
                                        velocidade e trajeto dos veículos,
                                        garantindo total visibilidade da
                                        operação da frota.
                                    </p>
                                </div>

                                {/* Feature 2 */}
                                <div className="group">
                                    <div className="mb-3 flex items-center gap-2 text-lg font-bold text-slate-900">
                                        <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                                        <h3>Controle de Combustível</h3>
                                    </div>
                                    <p className="text-sm leading-relaxed text-slate-500">
                                        Medição precisa do consumo de
                                        combustível com detecção automática de
                                        possíveis furtos e anomalias
                                        operacionais.
                                    </p>
                                </div>

                                {/* Feature 3 */}
                                <div className="group">
                                    <div className="mb-3 flex items-center gap-2 text-lg font-bold text-slate-900">
                                        <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                                        <h3>Segurança Total</h3>
                                    </div>
                                    <p className="text-sm leading-relaxed text-slate-500">
                                        Identificação de desvios de rota,
                                        paradas suspeitas e eventos críticos,
                                        com registros claros como data, hora e
                                        veículo afetado.
                                    </p>
                                </div>

                                {/* Feature 4 */}
                                <div className="group">
                                    <div className="mb-3 flex items-center gap-2 text-lg font-bold text-slate-900">
                                        <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                                        <h3>Ação Remota e Gravação</h3>
                                    </div>
                                    <p className="text-sm leading-relaxed text-slate-500">
                                        Relé integrado para bloqueio remoto do
                                        veículo e câmeras embarcadas que enviam
                                        registros da cabine automaticamente ao
                                        servidor.
                                    </p>
                                </div>
                            </div>

                            <a
                                href="/dash/register"
                                className="mt-10 flex w-fit items-center gap-2 rounded-full bg-card-dark px-6 py-3 font-medium text-white shadow-lg transition-all hover:bg-card-dark/80 hover:shadow-xl"
                            >
                                Explorar a Plataforma <ArrowRight size={18} />
                            </a>
                        </div>

                        {/* Right Column - The High-Tech Card */}
                        <div className="relative h-full min-h-[600px]">
                            <div className="group relative h-full overflow-hidden rounded-[2rem] border bg-card-dark p-4 shadow-2xl">
                                {/* Background Image inside Card (Map) */}
                                <div className="absolute inset-0">
                                    {/* Close up city map styled to look like dark mode interface */}
                                    <img
                                        src="/assets/map.jpg"
                                        alt="Live City Map"
                                        className="h-full w-full object-cover transition-transform duration-[20s] group-hover:scale-110"
                                    />
                                    {/* Lighter overlay for brighter map */}
                                    <div className="absolute inset-0 bg-slate-900/20 mix-blend-color"></div>
                                    <div className="absolute inset-0 bg-gradient-to-tl from-primary via-transparent to-transparent"></div>

                                    {/* Driver Pins Layer */}
                                    <div className="absolute inset-0 z-0">
                                        {/* Driver 1 - Top Left */}
                                        <div className="absolute top-[25%] left-[20%] -translate-x-1/2 -translate-y-1/2 transform">
                                            <div className="group/pin relative">
                                                <div className="absolute -inset-4 rounded-full bg-teal-500/20 opacity-0 blur-xl transition-opacity group-hover/pin:opacity-100"></div>
                                                <div className="relative z-10 h-10 w-10 overflow-hidden rounded-full border-2 border-teal-500 bg-slate-800 shadow-[0_0_15px_rgba(20,184,166,0.5)]">
                                                    <img
                                                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop"
                                                        alt="Driver"
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                                <div className="absolute -inset-1 animate-ping rounded-full border border-teal-500 opacity-20"></div>
                                                <div className="absolute -bottom-8 left-1/2 z-20 -translate-x-1/2 rounded bg-slate-900/90 px-2 py-1 text-[10px] whitespace-nowrap text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/pin:opacity-100">
                                                    Carlos M. • 45km/h
                                                </div>
                                            </div>
                                        </div>

                                        {/* Driver 2 - Center Right */}
                                        <div className="absolute top-[45%] right-[25%] -translate-x-1/2 -translate-y-1/2 transform">
                                            <div className="group/pin relative">
                                                <div className="absolute -inset-4 rounded-full bg-teal-500/20 opacity-0 blur-xl transition-opacity group-hover/pin:opacity-100"></div>
                                                <div className="relative z-10 h-10 w-10 overflow-hidden rounded-full border-2 border-teal-500 bg-slate-800 shadow-[0_0_15px_rgba(20,184,166,0.5)]">
                                                    <img
                                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop"
                                                        alt="Driver"
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                                <div className="animation-delay-500 absolute -inset-1 animate-ping rounded-full border border-teal-500 opacity-20"></div>
                                                <div className="absolute -bottom-8 left-1/2 z-20 -translate-x-1/2 rounded bg-slate-900/90 px-2 py-1 text-[10px] whitespace-nowrap text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/pin:opacity-100">
                                                    Ana P. • 60km/h
                                                </div>
                                            </div>
                                        </div>

                                        {/* Driver 3 - Bottom Left */}
                                        <div className="absolute bottom-[25%] left-[30%] -translate-x-1/2 -translate-y-1/2 transform">
                                            <div className="group/pin relative">
                                                <div className="absolute -inset-4 rounded-full bg-yellow-500/20 opacity-0 blur-xl transition-opacity group-hover/pin:opacity-100"></div>
                                                <div className="relative z-10 h-8 w-8 overflow-hidden rounded-full border-2 border-yellow-500/80 bg-slate-800 shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                                                    <img
                                                        src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=100&h=100&fit=crop"
                                                        alt="Driver"
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                                <div className="absolute -bottom-8 left-1/2 z-20 -translate-x-1/2 rounded bg-slate-900/90 px-2 py-1 text-[10px] whitespace-nowrap text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/pin:opacity-100">
                                                    Roberto S. • Parado
                                                </div>
                                            </div>
                                        </div>

                                        {/* Driver 4 - Top Center/Right */}
                                        <div className="absolute top-[15%] right-[40%] -translate-x-1/2 -translate-y-1/2 transform">
                                            <div className="group/pin relative">
                                                <div className="absolute -inset-4 rounded-full bg-teal-500/20 opacity-0 blur-xl transition-opacity group-hover/pin:opacity-100"></div>
                                                <div className="relative z-10 h-8 w-8 overflow-hidden rounded-full border-2 border-teal-500/80 bg-slate-800 shadow-[0_0_15px_rgba(20,184,166,0.3)]">
                                                    <img
                                                        src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop"
                                                        alt="Driver"
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                                <div className="animation-delay-700 absolute -inset-1 animate-ping rounded-full border border-teal-500 opacity-20"></div>
                                                <div className="absolute -bottom-8 left-1/2 z-20 -translate-x-1/2 rounded bg-slate-900/90 px-2 py-1 text-[10px] whitespace-nowrap text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/pin:opacity-100">
                                                    Marcos D. • 32km/h
                                                </div>
                                            </div>
                                        </div>

                                        {/* Driver 5 - Far Bottom Right */}
                                        <div className="absolute right-[15%] bottom-[15%] -translate-x-1/2 -translate-y-1/2 transform">
                                            <div className="group/pin relative">
                                                <div className="absolute -inset-4 rounded-full bg-teal-500/20 opacity-0 blur-xl transition-opacity group-hover/pin:opacity-100"></div>
                                                <div className="relative z-10 h-9 w-9 overflow-hidden rounded-full border-2 border-teal-500/80 bg-slate-800 shadow-[0_0_15px_rgba(20,184,166,0.3)]">
                                                    <img
                                                        src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=100&h=100&fit=crop"
                                                        alt="Driver"
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                                <div className="animation-delay-300 absolute -inset-1 animate-ping rounded-full border border-teal-500 opacity-20"></div>
                                                <div className="absolute -bottom-8 left-1/2 z-20 -translate-x-1/2 rounded bg-slate-900/90 px-2 py-1 text-[10px] whitespace-nowrap text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/pin:opacity-100">
                                                    Julia K. • 55km/h
                                                </div>
                                            </div>
                                        </div>

                                        {/* Driver 6 - Middle Left */}
                                        <div className="absolute top-[60%] left-[10%] -translate-x-1/2 -translate-y-1/2 transform">
                                            <div className="group/pin relative">
                                                <div className="absolute -inset-4 rounded-full bg-teal-500/20 opacity-0 blur-xl transition-opacity group-hover/pin:opacity-100"></div>
                                                <div className="relative z-10 h-8 w-8 overflow-hidden rounded-full border-2 border-teal-500/80 bg-slate-800 shadow-[0_0_15px_rgba(20,184,166,0.3)]">
                                                    <img
                                                        src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&h=100&fit=crop"
                                                        alt="Driver"
                                                        className="h-full w-full object-cover"
                                                    />
                                                </div>
                                                <div className="animation-delay-1000 absolute -inset-1 animate-ping rounded-full border border-teal-500 opacity-20"></div>
                                                <div className="absolute -bottom-8 left-1/2 z-20 -translate-x-1/2 rounded bg-slate-900/90 px-2 py-1 text-[10px] whitespace-nowrap text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/pin:opacity-100">
                                                    Beatriz L. • 28km/h
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Grid Overlay Effect */}
                                <div
                                    className="pointer-events-none absolute inset-0 opacity-10"
                                    style={{
                                        backgroundImage:
                                            'linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px)',
                                        backgroundSize: '40px 40px',
                                    }}
                                ></div>

                                {/* Floating UI Elements */}

                                {/* Top Left Stats List */}
                                <div className="absolute right-8 bottom-8 z-10 flex flex-col gap-3">
                                    <div className="flex w-40 items-center justify-between rounded border border-white/5 bg-card-dark px-4 py-2.5 shadow-lg backdrop-blur-md">
                                        <span className="text-[10px] font-semibold tracking-wider text-white">
                                            VEÍCULOS
                                        </span>
                                        <span className="font-mono text-xs text-slate-200">
                                            1,245
                                        </span>
                                    </div>
                                    <div className="flex w-40 items-center justify-between rounded border border-white/5 bg-card-dark px-4 py-2.5 shadow-lg backdrop-blur-md">
                                        <span className="text-[10px] font-semibold tracking-wider text-white">
                                            LATÊNCIA
                                        </span>
                                        <span className="font-mono text-xs text-slate-200">
                                            &lt; 18ms
                                        </span>
                                    </div>
                                    <div className="flex w-40 items-center justify-between rounded border border-white/5 bg-card-dark px-4 py-2.5 shadow-lg backdrop-blur-md">
                                        <span className="text-[10px] font-semibold tracking-wider text-white">
                                            UPTIME
                                        </span>
                                        <span className="font-mono text-xs text-slate-200">
                                            99.99%
                                        </span>
                                    </div>
                                    <div className="flex w-40 items-center justify-between rounded border border-white/5 bg-card-dark px-4 py-2.5 shadow-lg backdrop-blur-md">
                                        <span className="text-[10px] font-semibold tracking-wider text-white">
                                            CAMERAS
                                        </span>
                                        <span className="font-mono text-xs text-slate-200">
                                            REC
                                        </span>
                                    </div>
                                </div>

                                {/* Top Right Graph */}
                                <div className="absolute top-8 right-8 z-10 w-48 rounded-xl border border-teal-500/20 bg-card-dark p-4 shadow-lg backdrop-blur-md">
                                    <div className="relative mb-2 flex h-12 w-full items-end justify-between gap-1">
                                        {/* SVG Line Graph */}
                                        <svg
                                            className="absolute inset-0 h-full w-full overflow-visible"
                                            preserveAspectRatio="none"
                                        >
                                            <path
                                                d="M0,40 Q10,35 20,20 T40,10 T60,25 T80,5 T100,15 T120,30 T140,5"
                                                fill="none"
                                                stroke="#14b8a6"
                                                strokeWidth="2"
                                            />
                                            <path
                                                d="M0,40 Q10,35 20,20 T40,10 T60,25 T80,5 T100,15 T120,30 T140,5 V50 H0 Z"
                                                fill="url(#gradient)"
                                                opacity="0.2"
                                            />
                                            <defs>
                                                <linearGradient
                                                    id="gradient"
                                                    x1="0%"
                                                    y1="0%"
                                                    x2="0%"
                                                    y2="100%"
                                                >
                                                    <stop
                                                        offset="0%"
                                                        stopColor="#14b8a6"
                                                    />
                                                    <stop
                                                        offset="100%"
                                                        stopColor="transparent"
                                                    />
                                                </linearGradient>
                                            </defs>
                                        </svg>

                                        {/* Small dots on graph */}
                                        <div className="absolute top-1 right-0 h-2 w-2 rounded-full bg-teal-400 shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                                    </div>
                                    <div className="mt-2 flex items-center justify-between border-t border-white/5 pt-2">
                                        <span className="text-[9px] font-semibold tracking-wider text-slate-400 uppercase">
                                            KMs Rodados Hoje
                                        </span>
                                        <div className="flex items-center gap-1.5">
                                            <div className="h-1.5 w-1.5 animate-pulse rounded-full bg-teal-500"></div>
                                            <span className="text-[9px] font-bold text-teal-400">
                                                85 KM
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}

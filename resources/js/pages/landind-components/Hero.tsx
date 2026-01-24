import { ArrowRight, Battery, Navigation, Wifi } from 'lucide-react';
import { useEffect, useRef } from 'react';

export default function Hero() {
    const videoRef = useRef<HTMLVideoElement>(null);

    useEffect(() => {
        if (videoRef.current) {
            videoRef.current.playbackRate = 0.8; // Slow down slightly for a more cinematic feel
            videoRef.current
                .play()
                .catch((e) => console.log('Autoplay prevented:', e));
        }
    }, []);

    return (
        <section className="relative flex min-h-screen w-full flex-col justify-center overflow-hidden px-6 pt-36 pb-12 md:px-12">
            {/* Main Content Container */}
            <div className="relative z-10 mx-auto grid h-full w-full max-w-7xl grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-0">
                {/* Left Column (Text) */}
                <div className="relative flex flex-col justify-center space-y-8 lg:col-span-5 lg:pr-12">
                    {/* Background Glow Effect - Light Mode */}
                    <div className="absolute top-1/2 left-0 -z-10 h-[500px] w-[500px] -translate-y-1/2 rounded-full bg-teal-200/30 blur-[120px]"></div>

                    <div className="mb-6 flex items-center gap-4">
                        <div className="h-[1px] w-8 bg-slate-300"></div>
                        <span className="text-xs tracking-[0.2em] text-slate-500 uppercase">
                            Hardware <br /> Integrado
                        </span>
                    </div>

                    <h1 className="animate-in text-5xl leading-[0.95] font-bold tracking-tight text-slate-900 delay-100 duration-700 fade-in slide-in-from-bottom-8 md:text-6xl lg:text-7xl">
                        CONTROLE <br />
                        <span className="text-slate-900">SUA FROTA</span>
                    </h1>

                    <p className="max-w-lg animate-in text-lg leading-relaxed font-medium text-slate-600 delay-200 duration-700 fade-in slide-in-from-bottom-8">
                        Integração completa de hardware e software para
                        minimizar furtos, monitorar trajetos e economizar
                        combustível. A inteligência que seu transporte precisa.
                    </p>

                    {/* Feature Icons Row */}
                    <div className="flex animate-in gap-8 py-2 delay-300 duration-700 fade-in slide-in-from-bottom-8">
                        <div className="flex items-center gap-2">
                            {/* <div className="rounded-md border border-slate-100 bg-white p-2 text-slate-900 shadow-sm">
                                <ShieldCheck size={20} />
                            </div> */}
                            <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                            <span className="text-sm font-bold text-slate-700">
                                Anti-Furto
                            </span>
                        </div>
                        <div className="flex items-center gap-2">
                            {/* <div className="rounded-md border border-slate-100 bg-white p-2 text-slate-900 shadow-sm">
                                <Cpu size={20} />
                            </div> */}

                            <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                            <span className="text-sm font-bold text-slate-700">
                                Telemetria
                            </span>
                        </div>
                        <div className="flex items-center gap-2">
                            {/* <div className="rounded-md border border-slate-100 bg-white p-2 text-slate-900 shadow-sm">
                                <MapPin size={20} />
                            </div> */}

                            <div className="h-2 w-2 rounded-full bg-primary shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                            <span className="text-sm font-bold text-slate-700">
                                Tracking
                            </span>
                        </div>
                    </div>

                    {/* Buttons */}
                    <div className="mt-2 flex animate-in flex-wrap items-center gap-4 delay-400 duration-700 fade-in slide-in-from-bottom-8">
                        <button className="group relative flex cursor-pointer items-center gap-2 rounded-full bg-gradient-to-tl from-primary/80 to-primary/10 px-8 py-4 font-bold text-slate-900 shadow-md transition-all hover:to-primary/40 hover:shadow-[0_10px_25px_rgba(52,211,153,0.4)]">
                            Começar Agora <Navigation size={18} />
                        </button>

                        <button className="flex items-center gap-2 rounded-md border border-slate-200 bg-white px-8 py-4 font-bold text-slate-900 shadow-sm transition-colors hover:bg-slate-50">
                            Ver Demo <ArrowRight size={18} />
                        </button>
                    </div>

                    {/* Bottom Left Small Card - Styled EXACTLY like reference */}
                    <div className="group relative w-full max-w-sm overflow-hidden rounded-[2rem] bg-card-dark p-8 shadow-2xl">
                        <div className="mb-2 flex items-center gap-3 opacity-50">
                            <span className="text-[10px] tracking-widest text-white uppercase">
                                03
                            </span>
                            <div className="h-[1px] w-6 bg-white/30"></div>
                        </div>

                        <h3 className="mb-2 text-3xl leading-tight font-normal text-white">
                            Segurança
                            <br />
                            Total.
                        </h3>

                        <p className="max-w-[180px] text-xs leading-relaxed text-slate-400">
                            Minimiza furtos, detecta paradas e bloqueia o motor.
                        </p>
                    </div>
                </div>

                {/* Right Column (Visuals) */}
                <div className="relative flex flex-col justify-between pt-12 pl-0 lg:col-span-7 lg:pt-0 lg:pl-12">
                    {/* The BIG White Card - Blank with Shadow */}
                    <div className="relative flex h-[500px] w-full flex-col overflow-hidden rounded-[2.5rem] border border-slate-100 bg-white shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] lg:h-[600px]">
                        {/* Header of the white card */}
                        <div className="flex w-full items-center justify-between px-8 py-4 text-slate-400">
                            <div className="flex gap-4">
                                <Wifi size={22} className="text-teal-600" />
                                <Battery size={22} className="text-teal-600" />
                            </div>

                            <div className="z flex items-center gap-2 rounded-full border border-slate-200 bg-white/90 px-4 py-2 shadow-sm backdrop-blur-md">
                                <div className="h-2 w-2 animate-pulse rounded-full bg-green-500"></div>
                                <span className="text-[10px] font-bold tracking-widest text-slate-600 uppercase">
                                    Connected
                                </span>
                            </div>
                        </div>

                        {/* Video Body */}
                        <div className="relative h-full w-full flex-1 bg-slate-100">
                            {/* Video Element */}
                            <video
                                ref={videoRef}
                                className="absolute inset-0 h-full w-full object-cover opacity-90 grayscale-[10%]"
                                autoPlay
                                loop
                                muted
                                playsInline
                            >
                                {/* Reliable Pexels URL for City Traffic */}
                                <source
                                    src="/assets/hero.mp4"
                                    type="video/mp4"
                                />
                            </video>

                            {/* Overlay to keep the card feel but show video */}
                            <div className="pointer-events-none absolute inset-0 bg-white/10 mix-blend-overlay"></div>
                        </div>
                    </div>

                    {/* Bottom Right Wide Card - Styled EXACTLY like reference */}
                    <div className="relative mt-8 flex flex-col items-center justify-between overflow-hidden rounded-[2.5rem] bg-card-dark p-8 shadow-2xl md:flex-row md:px-10 md:py-8">
                        {/* Left side content */}
                        <div className="relative z-10 mb-6 flex items-center gap-6 md:mb-0">
                            <div className="flex -space-x-4">
                                <div className="h-12 w-12 overflow-hidden rounded-full border-2 border-card-dark bg-slate-700">
                                    <img
                                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop"
                                        alt="User"
                                        className="h-full w-full object-cover grayscale"
                                    />
                                </div>
                                <div className="h-12 w-12 overflow-hidden rounded-full border-2 border-card-dark bg-slate-700">
                                    <img
                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop"
                                        alt="User"
                                        className="h-full w-full object-cover grayscale"
                                    />
                                </div>
                                <div className="flex h-12 w-12 items-center justify-center rounded-full border-2 border-card-dark bg-[#2A363B] text-[10px] font-bold text-teal-400">
                                    4K
                                </div>
                            </div>
                            <div>
                                <div className="text-4xl leading-none font-light text-white">
                                    0
                                    <span className="text-2xl font-normal text-teal-500">
                                        ms
                                    </span>
                                </div>
                                <div className="mt-1 text-[10px] font-medium tracking-wider text-teal-500/80 uppercase">
                                    LATÊNCIA REDE
                                </div>
                            </div>
                        </div>

                        {/* Right side content */}
                        <div className="relative z-10 text-center md:text-right">
                            <div className="text-xl font-light tracking-tight text-white">
                                MONITORAMENTO{' '}
                                <span className="font-normal text-teal-500">
                                    EM TEMPO REAL
                                </span>
                            </div>
                            <button className="mt-2 border-b border-transparent pb-0.5 text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase transition-colors hover:border-white hover:text-white">
                                VER ESPECIFICAÇÕES
                            </button>
                        </div>

                        {/* Background decorative elements */}
                        <div className="pointer-events-none absolute -top-20 -right-10 h-64 w-64 rounded-full bg-teal-500/5 blur-3xl"></div>
                    </div>
                </div>
            </div>
        </section>
    );
}

import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useEffect, useState } from 'react';

const SLIDES = [
    {
        id: 1,
        tag: 'RELEASE 3.0',
        status: 'SYSTEM READY',
        titleLine1: 'Controle',
        titleLine2: 'Absoluto',
        description:
            'Uma interface unificada que une as leis físicas da sua frota à latência digital zero. Mapeamento de precisão na velocidade da decisão.',
        image: 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=2583&auto=format&fit=crop',
    },
    {
        id: 2,
        tag: 'LIVE FEED',
        status: 'SATELLITE LINK',
        titleLine1: 'Visão',
        titleLine2: 'Espacial',
        description:
            'Monitoramento em tempo real com feeds de vídeo e telemetria avançada para segurança de carga sem precedentes.',
        image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=2613&auto=format&fit=crop',
    },
    {
        id: 3,
        tag: 'ANALYTICS',
        status: 'OPTIMIZED',
        titleLine1: 'Dados',
        titleLine2: 'Críticos',
        description:
            'Transforme terabytes de dados brutos em insights acionáveis com nossa dashboard de inteligência artificial preditiva.',
        image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2670&auto=format&fit=crop',
    },
];

export default function CarouselSection() {
    const [current, setCurrent] = useState(0);

    const next = () => setCurrent((prev) => (prev + 1) % SLIDES.length);
    const prev = () =>
        setCurrent((prev) => (prev - 1 + SLIDES.length) % SLIDES.length);

    useEffect(() => {
        const timer = setInterval(next, 6000);
        return () => clearInterval(timer);
    }, []);

    return (
        <section className="overflow-hidden">
            {/* Full width container with small margin */}
            <div className="mx-4 md:mx-8 lg:mx-12">
                <div className="group relative h-[600px] w-full overflow-hidden rounded-[1rem] shadow-2xl md:aspect-video md:h-[700px] md:rounded-[2.5rem]">
                    {/* Slides */}
                    {SLIDES.map((slide, index) => (
                        <div
                            key={slide.id}
                            className={`absolute inset-0 transition-opacity duration-1000 ease-in-out ${
                                index === current
                                    ? 'z-10 opacity-100'
                                    : 'z-0 opacity-0'
                            }`}
                        >
                            {/* Background Image */}
                            <img
                                src={slide.image}
                                alt={slide.titleLine1}
                                className="h-full w-full object-cover opacity-80"
                            />

                            {/* Overlay Gradient - Matches brand but mimics reference contrast */}
                            <div className="absolute inset-0 bg-gradient-to-tr from-primary via-emerald-600/80 to-transparent mix-blend-multiply md:from-emerald-600 md:via-primary/30 md:to-transparent md:mix-blend-normal" />

                            {/* Dark gradient for text readability */}
                            <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60" />

                            {/* Content Layout */}
                            <div className="absolute inset-0 flex flex-col justify-end px-8 md:px-20 lg:px-32">
                                <div className="max-w-3xl">
                                    {/* Top Tags */}
                                    {/* <div className="mb-6 flex items-center gap-4 md:mb-8">
                                        <span className="border border-white/20 bg-white/20 px-2 py-0.5 font-mono text-[10px] font-bold tracking-widest text-white uppercase backdrop-blur-sm">
                                            {slide.tag}
                                        </span>
                                        <span className="font-mono text-[10px] font-bold tracking-widest text-emerald-950/60 uppercase">
                                            {slide.status}
                                        </span>
                                    </div> */}

                                    {/* Titles - Big & Bold */}
                                    <h2 className="mb-8 text-6xl leading-[0.85] font-bold tracking-tighter select-none md:text-7xl lg:text-9xl">
                                        {/* Line 1: Dark */}
                                        <span className="block text-emerald-950">
                                            {slide.titleLine1}
                                        </span>
                                        {/* Line 2: White */}
                                        <span className="block text-white opacity-95">
                                            {slide.titleLine2}
                                        </span>
                                    </h2>

                                    {/* Description */}
                                    <p className="mb-12 max-w-xl text-lg leading-relaxed font-medium text-white/90 md:text-xl">
                                        {slide.description}
                                    </p>
                                </div>
                            </div>
                        </div>
                    ))}

                    {/* Navigation Arrows */}
                    <button
                        onClick={prev}
                        className="absolute top-0 bottom-0 left-0 z-30 flex w-24 cursor-pointer items-center justify-center opacity-0 transition-all group-hover:opacity-100 hover:bg-gray-300/30"
                    >
                        <ChevronLeft
                            size={48}
                            className="text-card-dark opacity-0 drop-shadow-lg transition-all group-hover:opacity-100"
                        />
                    </button>
                    <button
                        onClick={next}
                        className="absolute top-0 right-0 bottom-0 z-30 flex w-24 cursor-pointer items-center justify-center opacity-0 transition-all group-hover:opacity-100 hover:bg-gray-300/30"
                    >
                        <ChevronRight
                            size={48}
                            className="text-card-dark opacity-0 drop-shadow-lg transition-all group-hover:opacity-100"
                        />
                    </button>

                    {/* Pagination Lines */}
                    <div className="absolute right-8 bottom-8 z-30 flex gap-1">
                        {SLIDES.map((_, idx) => (
                            <div
                                key={idx}
                                onClick={() => setCurrent(idx)}
                                className={`h-1 cursor-pointer transition-all duration-500 ${idx === current ? 'w-12 bg-white' : 'w-4 bg-white/30'}`}
                            />
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}

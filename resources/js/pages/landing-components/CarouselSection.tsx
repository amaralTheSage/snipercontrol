import React, { useEffect, useState } from 'react';
import { ArrowRight, ChevronRight, CheckCircle2 } from 'lucide-react';

const SLIDES = [
    {
        id: 1,
        titleLine1: 'Segurança',
        titleLine2: 'do Motorista',
        description:
            'Monitoramento contínuo do veículo e do condutor com tecnologia proprietária, garantindo maior segurança operacional e resposta rápida a situações de risco.',
        image: 'https://plus.unsplash.com/premium_photo-1664695368767-c42483a0bda1?q=80&w=1472&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
    },
    {
        id: 2,
        titleLine1: 'Segurança',
        titleLine2: 'da Carga',
        description:
            'Visão em tempo real com câmeras embarcadas e telemetria avançada para prevenção de furtos e desvios não autorizados.',
        image: 'https://plus.unsplash.com/premium_photo-1682144324433-ae1ee89a0238?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fA%3D%3D',
    },
    {
        id: 3,
        titleLine1: 'Controle',
        titleLine2: 'de Combustível',
        description:
            'Medição precisa do consumo de combustível através de hardware e software próprios, com alertas e relatórios para redução de perdas e fraudes.',
        image: 'https://images.unsplash.com/photo-1752986314569-b1b2138ecea9?q=80&w=1646&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
    },
];

export default function CarouselSection() {
    const [current, setCurrent] = useState(0);

    const next = () => setCurrent((prev) => (prev + 1) % SLIDES.length);

    useEffect(() => {
        const timer = setInterval(next, 6000);
        return () => clearInterval(timer);
    }, []);

    return (
        // Changed: Removed fixed h-screen to allow scrolling on mobile. 
        // Used min-h-screen to ensure it covers viewport if content is short.
        <section className="flex min-h-screen w-full flex-col lg:flex-row ">
            
            {/* CAROUSEL SIDE (LEFT/TOP) */}
            {/* Changed: 
                - Mobile: h-[50vh] or min-h-[500px] ensures the image is visible but leaves room for content below.
                - Desktop: h-auto w-1/2 (sticky behavior handled by flex stretch) 
            */}
            <div className="group relative h-[55vh] min-h-[450px] w-full overflow-hidden bg-slate-900 lg:h-auto lg:min-h-screen lg:w-1/2">
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
                            alt={`${slide.titleLine1} ${slide.titleLine2}`}
                            className="h-full w-full object-cover transition-transform duration-[8000ms] ease-linear group-hover:scale-110"
                        />

                        {/* Gradient Overlays */}
                        <div className="absolute inset-0 bg-gradient-to-tr from-slate-900/95 via-emerald-900/50 to-transparent mix-blend-multiply" />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80" />

                        {/* Slide Content */}
                        {/* Changed: Adjusted padding for mobile (p-6) vs desktop (p-20) */}
                        <div className="absolute inset-0 flex flex-col justify-end p-6 pb-16 md:px-12 md:pb-20 lg:px-20 lg:py-8 lg:pb-24">
                            <h2 className="mb-3 text-4xl font-bold tracking-tighter text-white select-none md:text-5xl lg:text-7xl lg:leading-[0.9]">
                                <span className="block drop-shadow-md">
                                    {slide.titleLine1}
                                </span>
                                <span className="block bg-gradient-to-r from-emerald-400 to-cyan-300 bg-clip-text text-transparent opacity-95 drop-shadow-md filter">
                                    {slide.titleLine2}
                                </span>
                            </h2>
                            {/* Changed: Font size responsive, limited width */}
                            <p className="max-w-md text-base font-light leading-relaxed text-slate-200/90 drop-shadow-sm md:text-lg lg:max-w-xl lg:text-xl">
                                {slide.description}
                            </p>
                        </div>
                    </div>
                ))}

                {/* Pagination Indicators */}
                {/* Changed: Position relative to container bottom */}
                <div className="absolute bottom-6 left-6 z-30 flex gap-2 md:left-12 lg:bottom-10 lg:left-20">
                    {SLIDES.map((_, idx) => (
                        <button
                            key={idx}
                            onClick={() => setCurrent(idx)}
                            className={`h-1.5 rounded-full shadow-sm transition-all duration-500 ${
                                idx === current
                                    ? 'w-10 bg-emerald-400'
                                    : 'w-2.5 bg-white/30 hover:bg-white/50'
                            }`}
                            aria-label={`Go to slide ${idx + 1}`}
                        />
                    ))}
                </div>
            </div>

            {/* DEMO SIDE (RIGHT/BOTTOM) */}
            {/* Changed: 
                - Mobile: w-full p-6 (normal flow)
                - Desktop: w-1/2 p-24 (centered vertically)
            */}
            <div
                id="demos"
                className="relative flex w-full flex-col items-center justify-center  p-6 py-12 md:p-12 lg:h-auto lg:min-h-screen lg:w-1/2 lg:p-24"
            >
                <div className="relative z-10 flex w-full max-w-xl flex-col">
                    
                    {/* Image Card */}
                   
                         <div className="absolute inset-0 z-10 bg-gradient-to-t from-black/40 to-transparent opacity-0 transition-opacity group-hover:opacity-100"/>
              <div className="relative aspect-[16/9] overflow-hidden bg-background"> <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimg.freepik.com%2Fpremium-photo%2Ftwo-npf-type-rechargeable-batteries-isolated-white-background-electronics-camera-equipment_191623-232.jpg" alt="Tecnologia de ponta" className="h-64 w-full object-cover mix-blend-multiply" /> </div> 
                        <div className="absolute bottom-4 left-4 z-20 flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white backdrop-blur-md opacity-0 transition-opacity group-hover:opacity-100">
                          
                    </div>

                    {/* Text and CTA */}
                    <div>
                    

                        <h2 className="mb-4 text-3xl font-bold tracking-tight text-slate-900 md:text-4xl lg:mb-6 lg:text-5xl lg:leading-[1.1]">
                            Teste na prática com um{' '}
                            <span className="text-slate-400 decoration-emerald-200/50 decoration-4 underline-offset-4">
                                Período gratuito
                            </span>
                        </h2>

                        <p className="mb-8 text-lg font-medium leading-relaxed text-slate-500 md:text-xl lg:mb-10">
                            Experimente a plataforma completa durante nosso
                            período de demonstração. Veja os resultados antes de
                            qualquer compromisso financeiro.
                        </p>

                        <div className="flex flex-col gap-4 sm:flex-row">
                            <button className="group relative flex cursor-pointer items-center justify-center gap-2 rounded-full bg-slate-900 px-8 py-4 text-base font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:-translate-y-1 hover:bg-slate-800 hover:shadow-xl lg:text-lg">
                                Agendar Demo
                                <ArrowRight className="h-5 w-5 transition-transform group-hover:translate-x-1" />
                            </button>
                            
                             <button className="flex cursor-pointer items-center justify-center gap-2 rounded-full border border-slate-200 px-8 py-4 text-base font-semibold text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900 lg:text-lg">
                                Ver Planos
                            </button>
                        </div>

            
                    </div>
                </div>
            </div>
        </section>
    );
}
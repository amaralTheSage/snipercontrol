import { ArrowRight, ChevronLeft, ChevronRight } from 'lucide-react';
import { useEffect, useState } from 'react';
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
    const prev = () =>
        setCurrent((prev) => (prev - 1 + SLIDES.length) % SLIDES.length);

    useEffect(() => {
        const timer = setInterval(next, 6000);
        return () => clearInterval(timer);
    }, []);

  
    return (
        <section className="flex flex-col lg:flex-row w-full min-h-screen lg:h-[900px] overflow-hidden ">
            {/* CAROUSEL SIDE (LEFT) */}
            <div className="relative w-full lg:w-1/2 h-[600px] lg:h-auto overflow-hidden group">
                {SLIDES.map((slide, index) => (
                    <div
                        key={slide.id}
                        className={`absolute inset-0 transition-opacity duration-1000 ease-in-out ${
                            index === current ? 'z-10 opacity-100' : 'z-0 opacity-0'
                        }`}
                    >
                        {/* Background Image with Zoom Effect */}
                        <img
                            src={slide.image}
                            alt={slide.titleLine1 + ' ' + slide.titleLine2}
                            className="h-full w-full object-cover transition-transform duration-[8000ms] ease-linear scale-105 group-hover:scale-110"
                        />
                        
                        {/* Complex Gradient Overlays for Readability and Aesthetic */}
                        <div className="absolute inset-0 bg-gradient-to-tr from-slate-900/90 via-emerald-900/40 to-transparent mix-blend-multiply" />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-90" />

                        {/* Slide Content */}
                        <div className="absolute inset-0 flex flex-col justify-end px-8 py-8 md:px-16 lg:px-20 pb-20">


                            <h2 className="text-6xl md:text-7xl  font-bold tracking-tighter leading-[0.9] mb-2 select-none">
                                <span className="block text-white drop-shadow-lg">{slide.titleLine1}</span>
                                <span className="block text-transparent bg-clip-text bg-gradient-to-r py-2 from-emerald-400 to-cyan-300 opacity-95 filter drop-shadow-lg">{slide.titleLine2}</span>
                            </h2>
                            <p className="text-lg md:text-xl text-slate-100/90 max-w-xl leading-relaxed font-light drop-shadow-sm">
                                {slide.description}
                            </p>
                        </div>
                    </div>
                ))}
                
                {/* Pagination Indicators */}
                <div className="absolute left-8 lg:left-20 bottom-8 z-30 flex gap-2">
                    {SLIDES.map((_, idx) => (
                        <button
                            key={idx}
                            onClick={() => setCurrent(idx)}
                            className={`h-1.5 rounded-full transition-all duration-500 shadow-sm ${idx === current ? 'w-12 bg-emerald-400' : 'w-3 bg-white/30 hover:bg-white/50'}`}
                            aria-label={`Go to slide ${idx + 1}`}
                        />
                    ))}
                </div>
            </div>



            {/* DEMO SIDE (RIGHT) */}
            <div id="demos" className="w-full lg:w-1/2  flex items-center justify-center p-8 md:p-16 lg:p-24 relative">


                <div className="max-w-xl w-full relative z-10 flex flex-col gap-12">
                    {/* Image Card - Full Width of Column */}
<div className="relative overflow-hidden aspect-[16/9] bg-background">
  <img
    src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimg.freepik.com%2Fpremium-photo%2Ftwo-npf-type-rechargeable-batteries-isolated-white-background-electronics-camera-equipment_191623-232.jpg"
    alt="Tecnologia de ponta"
    className="w-full h-64 object-cover mix-blend-multiply"
  />
</div>


                    {/* Text and CTA */}
                    <div>
                        <h2 className="text-5xl lg:text-6xl font-bold tracking-tight leading-[1.05] mb-6 text-slate-900">
                            Teste na prática com um{" "}
                            <span className="text-slate-400">Período gratuito</span>
                        </h2>
                        
                        <p className="text-xl text-slate-500 leading-relaxed font-medium mb-10">
                            Experimente a plataforma completa durante nosso período de demonstração. Veja os resultados antes de qualquer compromisso.
                        </p>

                        <button className="group relative flex cursor-pointer items-center gap-2 rounded-full bg-gradient-to-tl from-primary/80 to-primary/10 px-8 py-4 font-bold text-slate-900 shadow-md transition-all hover:to-primary/40 hover:shadow-[0_10px_25px_rgba(52,211,153,0.4)]">
                            Agendar Demonstração
                            <ArrowRight className="w-5 h-5 transition-transform group-hover:translate-x-1 opacity-70 group-hover:opacity-100" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    );
}

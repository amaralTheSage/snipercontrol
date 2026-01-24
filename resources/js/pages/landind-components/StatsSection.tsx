import React from 'react';

const StatsSection: React.FC = () => {
    const stats = [
        {
            id: '01',
            value: '28%',
            description: 'Redução de Combustível',
            detail: 'Otimização de rotas e monitoramento de velocidade em tempo real.',
            company: 'TRANSLOG SUL',
        },
        {
            id: '02',
            value: 'ZERO',
            description: 'Furtos Registrados',
            detail: 'Bloqueio imediato do motor e recuperação de carga em 100% dos sinistros.',
            company: 'SILVA TRANSPORTES',
        },
        {
            id: '03',
            value: '15%',
            description: 'Aumento de Entregas',
            detail: 'Maior eficiência logística com menor tempo de ociosidade da frota.',
            company: 'LOGÍSTICA BR',
        },
    ];

    return (
        <section className="relative w-full overflow-hidden border-t border-white/5 bg-[#030f0b] py-32">
            {/* Vertical Grid Lines - Exactly like reference */}
            <div className="pointer-events-none absolute inset-0 flex justify-center opacity-10">
                <div className="grid h-full w-full max-w-7xl grid-cols-1 border-r border-primary/30 md:grid-cols-3">
                    <div className="h-full border-l border-primary/30"></div>
                    <div className="hidden h-full border-l border-primary/30 md:block"></div>
                    <div className="hidden h-full border-l border-primary/30 md:block"></div>
                </div>
            </div>

            <div className="relative z-10 mx-auto max-w-7xl px-6">
                {/* Header Section */}
                <div className="mb-24 grid grid-cols-1 gap-12 lg:grid-cols-2">
                    <div>
                        <div className="mb-6 flex items-center gap-4">
                            <span className="rounded border border-primary/20 bg-[#062c23] px-2 py-1 text-[10px] font-bold text-primary">
                                03
                            </span>
                            <div className="h-px w-12 bg-emerald-900"></div>
                            <span className="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase">
                                Impacto Real
                            </span>
                        </div>
                        <h2 className="text-5xl leading-tight font-light tracking-tight text-white md:text-6xl">
                            Resultados{' '}
                            <span className="text-primary">Comprovados</span>
                        </h2>
                    </div>
                    <div className="flex items-end pb-2">
                        <p className="max-w-md text-sm leading-relaxed text-slate-400">
                            Números auditados de parceiros que transformaram a
                            gestão de suas frotas utilizando a tecnologia de
                            telemetria e segurança SniperControl.
                        </p>
                    </div>
                </div>

                {/* Cards Grid */}
                <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
                    {stats.map((stat) => (
                        <div
                            key={stat.id}
                            className="group relative flex h-[360px] flex-col justify-between overflow-hidden rounded-3xl border border-emerald-900/30 bg-[#051612] p-8 transition-all duration-500 hover:border-primary/50"
                        >
                            {/* Hover Glow Effect */}
                            <div className="pointer-events-none absolute top-0 left-0 h-full w-full bg-primary/5 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>
                            <div className="absolute -top-20 -right-20 h-40 w-40 rounded-full bg-primary/10 blur-3xl transition-all duration-500 group-hover:bg-primary/20"></div>

                            <div className="relative z-10">
                                {/* Stat Value */}
                                <div className="mb-6 text-6xl font-light tracking-tighter text-white">
                                    {stat.value}
                                </div>

                                {/* Description */}
                                <h3 className="mb-4 text-xl font-medium text-emerald-400">
                                    {stat.description}
                                </h3>
                                <p className="max-w-[90%] text-xs leading-relaxed text-slate-400 opacity-80">
                                    {stat.detail}
                                </p>
                            </div>

                            {/* Footer / Company */}
                            <div className="relative z-10 mt-auto flex items-center justify-between border-t border-emerald-900/30 pt-6 transition-colors group-hover:border-primary/30">
                                <span className="text-[10px] font-bold tracking-[0.25em] text-emerald-700 uppercase transition-colors group-hover:text-emerald-400">
                                    {stat.company}
                                </span>
                                {/* Subtle arrow like reference */}
                                <span className="transform text-emerald-900 transition-all duration-300 group-hover:translate-x-1 group-hover:text-emerald-400">
                                    →
                                </span>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
};

export default StatsSection;

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
        <section className="relative w-full overflow-hidden border-t border-white/5 py-32">
            <div className="relative z-10 mx-auto max-w-7xl px-6">
                {/* Header Section */}
                <div className="mb-24 grid grid-cols-1 gap-12 lg:grid-cols-2">
                    <div>
                        <div className="mb-6 flex items-center gap-4">
                            <span className="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase">
                                Impacto Real
                            </span>
                            <div className="h-px w-12 bg-primary"></div>
                        </div>
                        <h2 className="tracking- text-5xl leading-tight font-light md:text-6xl">
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
                            className="group relative flex h-[340px] flex-col justify-between overflow-hidden rounded-3xl border border-primary/30 bg-card-dark p-8 transition-all duration-500 hover:border-primary/50"
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
                                <h3 className="mb-4 text-xl font-medium text-primary">
                                    {stat.description}
                                </h3>
                                <p className="max-w-[90%] text-xs leading-relaxed text-slate-400 opacity-80">
                                    {stat.detail}
                                </p>
                            </div>

                            {/* Footer / Company */}
                            <div className="relative z-10 mt-auto flex items-center justify-between border-t border-primary/30 pt-6 transition-colors group-hover:border-primary/30">
                                <span className="text-[10px] font-bold tracking-[0.25em] text-primary uppercase transition-colors group-hover:text-primary">
                                    {stat.company}
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

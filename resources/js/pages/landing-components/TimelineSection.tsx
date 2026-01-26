export default function TimelineSection() {
    return (
        <section className="relative overflow-hidden py-24">
            <div className="relative z-10 mx-auto max-w-7xl px-6 md:px-12">
                {/* Section Header */}
                <div className="mb-32 text-center">
                    <span className="mb-4 block text-xs font-bold tracking-[0.2em] text-primary uppercase">
                        Como Funciona
                    </span>
                    <h2 className="text-4xl font-normal tracking-tight text-slate-900 md:text-5xl">
                        O Processo de Dados SniperControl
                    </h2>
                </div>

                {/* Timeline Container */}
                <div className="relative">
                    {/* Central Line */}
                    <div className="absolute top-0 bottom-0 left-4 w-px bg-slate-300 md:left-1/2 md:-translate-x-1/2"></div>

                    {/* STAGE 01 */}
                    <div className="relative mb-24 grid grid-cols-1 items-center gap-8 md:mb-48 md:grid-cols-2 md:gap-24">
                        {/* Center Dot */}
                        <div className="absolute top-0 left-4 z-10 h-3 w-3 translate-y-1.5 rounded-full border-2 border-slate-900 bg-[#fafaf9] md:top-auto md:left-1/2 md:-translate-x-1/2"></div>

                        {/* Left Content (Title) */}
                        <div className="order-1 pl-12 md:pl-0 md:text-right">
                            <span className="mb-3 block text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                                Etapa 01
                            </span>
                            <h3 className="text-3xl leading-tight text-slate-900 md:text-4xl">
                                Captura de <br />
                                <span className="font-light text-slate-600">
                                    Dados
                                </span>
                            </h3>
                        </div>

                        {/* Right Content (Description) */}
                        <div className="order-2 pl-12 md:pl-0">
                            <p className="max-w-sm text-lg leading-relaxed text-slate-500">
                                O hardware instalado captura dados vitais sobre
                                o veículo em tempo real, incluindo velocidade,
                                consumo e telemetria do motor.
                            </p>
                            <div className="mt-6 text-[10px] font-bold tracking-widest text-primary uppercase">
                                Captura
                            </div>
                        </div>
                    </div>

                    {/* STAGE 02 */}
                    <div className="relative mb-24 grid grid-cols-1 items-center gap-8 md:mb-48 md:grid-cols-2 md:gap-24">
                        {/* Center Dot */}
                        <div className="absolute top-0 left-4 z-10 h-3 w-3 translate-y-1.5 rounded-full border-2 border-slate-900 bg-[#fafaf9] md:top-auto md:left-1/2 md:-translate-x-1/2"></div>

                        {/* Left Content (Description) - Swapped for Stage 2 */}
                        <div className="order-2 pl-12 md:order-1 md:pl-0 md:text-right">
                            <p className="ml-auto max-w-sm text-lg leading-relaxed text-slate-500">
                                Quando o veículo entra em área com conexão Wi-Fi
                                ou Dados Móveis, o sistema transmite
                                automaticamente o pacote de dados criptografado
                                ao nosso servidor.
                            </p>
                            <div className="mt-6 text-[10px] font-bold tracking-widest text-primary uppercase">
                                Transmissão
                            </div>
                        </div>

                        {/* Right Content (Title) - Swapped for Stage 2 */}
                        <div className="order-1 pl-12 md:order-2 md:pl-0">
                            <span className="mb-3 block text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                                Etapa 02
                            </span>
                            <h3 className="text-3xl leading-tight text-slate-900 md:text-4xl">
                                Conexão & <br />
                                <span className="font-light text-slate-600">
                                    Envio
                                </span>
                            </h3>
                        </div>
                    </div>

                    {/* STAGE 03 */}
                    <div className="relative grid grid-cols-1 items-center gap-8 md:grid-cols-2 md:gap-24">
                        {/* Center Dot */}
                        <div className="absolute top-0 left-4 z-10 h-3 w-3 translate-y-1.5 rounded-full border-2 border-slate-900 bg-slate-900 md:top-auto md:left-1/2 md:-translate-x-1/2"></div>

                        {/* Left Content (Title) */}
                        <div className="order-1 pl-12 md:pl-0 md:text-right">
                            <span className="mb-3 block text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                                Etapa 03
                            </span>
                            <h3 className="text-3xl leading-tight text-slate-900 md:text-4xl">
                                Visualização <br />
                                <span className="font-light text-slate-600">
                                    no Dashboard
                                </span>
                            </h3>
                        </div>

                        {/* Right Content (Description) */}
                        <div className="order-2 pl-12 md:pl-0">
                            <p className="max-w-sm text-lg leading-relaxed text-slate-500">
                                Os dados são processados e disponibilizados
                                instantaneamente para os administradores da
                                frota, permitindo decisões rápidas e assertivas.
                            </p>
                            <div className="mt-6 text-[10px] font-bold tracking-widest text-primary uppercase">
                                Análise
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}

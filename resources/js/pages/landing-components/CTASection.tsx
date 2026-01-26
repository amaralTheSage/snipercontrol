export default function CTASection() {
    return (
        <section className=" py-32">
            <div className="mx-auto max-w-7xl px-6 md:px-12">
                <div className="flex flex-col justify-between gap-16 lg:flex-row lg:items-end">
                    {/* Left Side */}
                    <div className="max-w-2xl">
                        <h2 className="mb-12 text-5xl leading-[0.95] font-bold tracking-tighter text-slate-900 md:text-6xl">
                            Comece sua <br />
                            jornada hoje
                        </h2>
                        <p className="text-sm font-medium text-slate-500">
                            Tem dúvidas? Fale com vendas (11) 4002-8922 ou{' '}
                            <a
                                href="#contact"
                                className="text-primary transition-colors hover:text-primary"
                            >
                                solicite uma ligação
                            </a>
                            .
                        </p>
                    </div>

                    {/* Right Side */}
                    <div className="flex flex-col items-start gap-12 pb-1 lg:items-end">
                        <div className="flex flex-wrap gap-4">
                            <button className="rounded-full bg-black px-8 py-4 text-sm font-bold text-white shadow-lg shadow-zinc-200 transition-all hover:scale-105 hover:bg-zinc-800 active:scale-95">
                                Ver planos e preços
                            </button>
                            <button className="rounded-full bg-black px-8 py-4 text-sm font-bold text-white shadow-lg shadow-zinc-200 transition-all hover:scale-105 hover:bg-zinc-800 active:scale-95">
                                Agendar demonstração
                            </button>
                            <button className="rounded-full bg-black px-8 py-4 text-sm font-bold text-white shadow-lg shadow-zinc-200 transition-all hover:scale-105 hover:bg-zinc-800 active:scale-95">
                                Ver em ação
                            </button>
                        </div>

                        <p className="text-sm font-medium text-slate-500">
                            Já é cliente?{' '}
                            <a
                                href="#login"
                                className="text-primary transition-colors hover:text-primary"
                            >
                                Faça login aqui
                            </a>
                            .
                        </p>
                    </div>
                </div>
            </div>
        </section>
    );
}

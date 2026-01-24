export default function Footer() {
    return (
        <footer className="border-t border-slate-100 bg-white py-24">
            <div className="mx-auto flex max-w-7xl items-center justify-between px-6">
                {/* Logo / Brand Name */}
                <div className="mb-12 flex items-center gap-2 select-none">
                    <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-black text-xs font-bold tracking-tighter text-white">
                        SC
                    </div>
                    <span className="text-xl font-bold tracking-tight text-slate-900">
                        SniperControl
                    </span>
                </div>

                {/* Pyramid Navigation */}
                <nav className="flex w-full flex-col items-end gap-4">
                    {/* Line 1: 1 Link */}
                    <div className="flex justify-center">
                        <a
                            href="#about"
                            className="px-4 py-2 text-sm font-medium text-slate-500 transition-colors hover:text-black"
                        >
                            Sobre Nós
                        </a>
                    </div>

                    {/* Line 2: 2 Links */}
                    <div className="flex justify-center gap-8">
                        <a
                            href="#features"
                            className="px-2 py-2 text-sm font-medium text-slate-500 transition-colors hover:text-black"
                        >
                            Recursos
                        </a>
                        <a
                            href="#hardware"
                            className="px-2 py-2 text-sm font-medium text-slate-500 transition-colors hover:text-black"
                        >
                            Hardware
                        </a>
                    </div>

                    {/* Line 3: 3 Links */}
                    <div className="flex justify-center gap-8">
                        <a
                            href="#privacy"
                            className="px-2 py-2 text-sm font-medium text-slate-500 transition-colors hover:text-black"
                        >
                            Privacidade
                        </a>
                        <a
                            href="#terms"
                            className="px-2 py-2 text-sm font-medium text-slate-500 transition-colors hover:text-black"
                        >
                            Termos
                        </a>
                        <a
                            href="#contact"
                            className="px-2 py-2 text-sm font-medium text-slate-500 transition-colors hover:text-black"
                        >
                            Contato
                        </a>
                    </div>
                </nav>
            </div>

            {/* Copyright */}
            <div className="text-center text-xs font-medium text-slate-400">
                © 2024 SniperControl Systems.
            </div>
        </footer>
    );
}

export default function Footer() {
    return (
        <footer className="py-24">
            <div className="mx-auto flex max-w-7xl items-center justify-between px-6">
                {/* Logo / Brand Name */}
                <img src="/assets/logo.svg" alt="" className="w-44" />

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
                © {new Date().getFullYear()} SniperControl Systems.
            </div>
        </footer>
    );
}

import { ArrowRight, Menu, X } from 'lucide-react';
import React, { useState } from 'react';

export default function Header(): React.ReactElement {
    const [isOpen, setIsOpen] = useState(false);

    const navItems = [
        { label: 'Hardware', href: '#hardware' },
        { label: 'Planos', href: '#pricing' },
        { label: 'Contato', href: '#contact' },
    ];

    return (
        <nav className="absolute top-0 right-0 left-0 z-50 bg-transparent px-6 py-8 md:px-12">
            <div className="relative mx-auto flex max-w-7xl items-center justify-between">
                {/* Left: Logo */}
                <img src="/assets/logo.svg" alt="" className="w-32" />

                {/* Center: Nav Items (Desktop) */}
                {/* Using absolute centering to ensure it's dead center regardless of logo/button widths */}
                <div className="absolute top-1/2 left-1/2 hidden -translate-x-1/2 -translate-y-1/2 items-center gap-10 md:flex">
                    {navItems.map((item) => (
                        <a
                            key={item.label}
                            href={item.href}
                            className="text-sm font-semibold text-slate-500 transition-colors hover:text-black"
                        >
                            {item.label}
                        </a>
                    ))}
                </div>

                {/* Right: CTA & Mobile Toggle */}
                <div className="flex items-center gap-4">
                    <a
                        href="#login"
                        className="hidden items-center gap-2 rounded-full bg-black px-6 py-3 text-sm font-bold text-white shadow-lg shadow-zinc-200 transition-all hover:scale-105 hover:bg-zinc-800 active:scale-95 md:flex"
                    >
                        Começar Agora <ArrowRight size={16} />
                    </a>

                    <button
                        className="p-1 text-black md:hidden"
                        onClick={() => setIsOpen(!isOpen)}
                    >
                        {isOpen ? <X size={24} /> : <Menu size={24} />}
                    </button>
                </div>
            </div>

            {/* Mobile Menu Dropdown */}
            {isOpen && (
                <div className="absolute top-full right-0 left-0 z-50 mt-2 flex animate-in flex-col gap-6 border-b border-slate-100 bg-white/95 p-6 shadow-xl backdrop-blur-md slide-in-from-top-2 md:hidden">
                    {navItems.map((item) => (
                        <a
                            key={item.label}
                            href={item.href}
                            className="text-lg font-medium text-slate-600 hover:text-black"
                            onClick={() => setIsOpen(false)}
                        >
                            {item.label}
                        </a>
                    ))}
                    <div className="h-px bg-slate-100" />
                    <button className="flex w-full items-center justify-center gap-2 rounded-full bg-black py-4 font-bold text-white shadow-lg transition-transform active:scale-95">
                        Começar Agora <ArrowRight size={16} />
                    </button>
                </div>
            )}
        </nav>
    );
}

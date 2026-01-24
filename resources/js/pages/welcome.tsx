import { useAppearance } from '@/hooks/use-appearance';
import type { SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import CarouselSection from './landind-components/CarouselSection';
import CTASection from './landind-components/CTASection';
import FeaturesSection from './landind-components/FeaturesSection';
import Footer from './landind-components/Footer';
import Header from './landind-components/Header';
import Hero from './landind-components/Hero';
import StatsSection from './landind-components/StatsSection';
import TimelineSection from './landind-components/TimelineSection';

export default function Welcome({
    canRegister = true,
}: {
    canRegister?: boolean;
}) {
    const { auth } = usePage<SharedData>().props;

    const { appearance, updateAppearance } = useAppearance();
    updateAppearance('light');

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link rel="stylesheet" />
            </Head>

            <div className="relative min-h-screen font-sans">
                <Header />

                {/* Grid Lines Background - Light Mode */}
                <div className="pointer-events-none absolute inset-0 z-0 flex h-full justify-between px-6 opacity-30 select-none md:px-12">
                    <div className="h-full w-px bg-slate-300"></div>
                    <div className="hidden h-full w-px bg-slate-300 md:block"></div>
                    <div className="hidden h-full w-px bg-slate-300 lg:block"></div>
                    <div className="hidden h-full w-px bg-slate-300 xl:block"></div>
                    <div className="h-full w-px bg-slate-300"></div>
                </div>

                <Hero />
                {/*  */}
                <FeaturesSection />

                <TimelineSection />

                <CarouselSection />

                <StatsSection />

                <CTASection />

                <Footer />
            </div>
        </>
    );
}

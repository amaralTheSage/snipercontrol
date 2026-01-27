import { useAppearance } from '@/hooks/use-appearance';
import type { SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import CarouselSection from './landing-components/CarouselSection';
import CTASection from './landing-components/CTASection';
import FeaturesSection from './landing-components/FeaturesSection';
import Footer from './landing-components/Footer';
import Graph from './landing-components/Graph';
import Header from './landing-components/Header';
import Hero from './landing-components/Hero';
import StatsSection from './landing-components/StatsSection';
import TimelineSection from './landing-components/TimelineSection';

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
            <Head>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link rel="stylesheet" />
            </Head>

            <div className="relative min-h-screen font-sans">
                <Header />

                {/* Grid Lines Background - Light Mode */}
                <div className="pointer-events-none absolute inset-0 z-0 flex h-full justify-between px-6 opacity-30 select-none md:px-12">
                    <div className="h-full w-px bg-teal-200"></div>
                    <div className="hidden h-full w-px bg-teal-200 md:block"></div>
                    <div className="hidden h-full w-px bg-teal-200 lg:block"></div>
                    <div className="hidden h-full w-px bg-teal-200 xl:block"></div>
                    <div className="h-full w-px bg-teal-200"></div>
                </div>

                <Hero />
                {/*  */}
                <FeaturesSection />

                <TimelineSection />

                <CarouselSection />

                <StatsSection />

                <Graph />

                <CTASection />

                <Footer />
            </div>
        </>
    );
}

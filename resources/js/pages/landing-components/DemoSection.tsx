import { ArrowRight } from "lucide-react";


export default function DemoSection(){
      return (
      <div className="">
        <div className="flex flex-col  items-center gap-16 lg:gap-24">
          
          {/* Image Column (Left) - Clean and Simple */}
          <div className="w-full lg:w-1/2">
            <div className="relative rounded-2xl overflow-hidden shadow-xl aspect-[4/3]">
              <img 
                src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fthumbs.dreamstime.com%2Fb%2Fblack-camera-batteries-technology-concept-349639426.jpg&f=1&nofb=1&ipt=3912241b4f100a4bdff68f00efc430374f5cc68abbdd7cad203df77281dd304e" 
                alt="Demonstração do produto" 
                className="w-full h-full object-cover"
              />
            </div>
          </div>

          {/* Text/CTA Column (Right) - Minimalist Typography */}
          <div className="w-full lg:w-1/2 text-left">
            
            <h2 className="text-5xl lg:text-6xl font-bold tracking-tight leading-[1.1] mb-6">
              <span className="text-slate-900">Teste na prática com um</span> <br />
              <span className="text-slate-400">Período gratuito</span>
            </h2>
            
            <p className="text-xl text-slate-500 mb-10 max-w-lg leading-relaxed font-medium">
              Experimente a plataforma completa durante nosso período de demonstração. Veja os resultados antes de qualquer compromisso.
            </p>

            <div>
                <button className="group relative flex cursor-pointer items-center gap-2 rounded-full bg-gradient-to-tl from-primary/80 to-primary/10 px-8 py-4 font-bold text-slate-900 shadow-md transition-all hover:to-primary/40 hover:shadow-[0_10px_25px_rgba(52,211,153,0.4)]">
                    Agendar Demonstração
                    <ArrowRight className="w-5 h-5 transition-transform group-hover:translate-x-1 opacity-70 group-hover:opacity-100" />
                </button>
            </div>
          </div>

        </div>
      </div>
  );
}
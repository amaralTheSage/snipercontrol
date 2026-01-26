import React, { useState, useMemo } from 'react';
import {
  ComposedChart,
  Line,
  Area,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  Legend
} from 'recharts';

interface PricingDataPoint {
  quantity: number;
  unitPrice: number;
  totalSavings: number;
  totalCost: number;
}

interface PricingTier {
  minQuantity: number;
  discountPercentage: number; // 0 to 1
}

const BASE_PRICE = 100;

const PRICING_TIERS: PricingTier[] = [
  { minQuantity: 0, discountPercentage: 0 },
  { minQuantity: 10, discountPercentage: 0.02 },
  { minQuantity: 50, discountPercentage: 0.10 },
  { minQuantity: 100, discountPercentage: 0.20 },
  { minQuantity: 250, discountPercentage: 0.25 },
  { minQuantity: 500, discountPercentage: 0.35 },
];

const MAX_QUANTITY_DEMO = 600;


export default function Graph(){
  const [hoverQuantity, setHoverQuantity] = useState<number | null>(null);

  // Generate smooth data for the graph
  const data: PricingDataPoint[] = useMemo(() => {
    const points: PricingDataPoint[] = [];
    for (let q = 1; q <= MAX_QUANTITY_DEMO; q += (q < 50 ? 2 : 10)) {
      // Logic: Power curve for a nice 45-ish degree aesthetic
      // Power of 0.7 gives a gentle curve that isn't too flat nor too steep
      const progress = q / MAX_QUANTITY_DEMO;
      const maxDiscount = 0.40; // Max 40% discount
      
      const discount = maxDiscount * Math.pow(progress, 0.7);
      
      const unitPrice = BASE_PRICE * (1 - discount);
      const retailCost = BASE_PRICE * q;
      const totalCost = unitPrice * q;
      const totalSavings = retailCost - totalCost;

      points.push({
        quantity: q,
        unitPrice: Number(unitPrice.toFixed(2)),
        totalSavings: Number(totalSavings.toFixed(2)),
        totalCost: Number(totalCost.toFixed(2))
      });
    }
    return points;
  }, []);

  const CustomTooltip = ({ active, payload, label }: any) => {
    if (active && payload && payload.length) {
      return (
        <div className="bg-card-dark p-4 border border-slate-700 shadow-2xl rounded-xl text-left">
          <p className="font-bold text-slate-200 mb-2">Quantidade: {label} un.</p>
          <div className="space-y-1">
            <p className="text-sm text-indigo-300 font-medium">
              Preço Unitário: R$ {payload[0].value}
            </p>
            <p className="text-sm text-teal-400 font-medium">
              Economia Total: R$ {payload[1].value}
            </p>
            <p className="text-xs text-slate-500 mt-2">
              Preço Padrão: R$ {BASE_PRICE}/un.
            </p>
          </div>
        </div>
      );
    }
    return null;
  };

  return (
    <section className="py-20  relative overflow-hidden">
      <div className="container mx-auto px-4 relative z-10">
        <div className="max-w-4xl mx-auto text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
            Preços ajustáveis conforma o tamanho da sua frota
          </h2>
          <p className="text-lg text-slate-600">
             Visualize o aumento da eficiência e redução de custos conforme você escala sua operação.
          </p>
        </div>

        {/* Dark Card Container */}
        <div className="bg-card-dark rounded-[2.5rem] shadow-2xl p-6 md:p-12 border border-slate-700/50 relative overflow-hidden">
          
          <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-teal-500/10 rounded-full blur-[100px] pointer-events-none -translate-y-1/2 translate-x-1/4"></div>

          <div className="flex flex-col md:flex-row justify-between items-end mb-8 relative z-10">
            <div>
              <h3 className="text-2xl font-semibold text-white mb-2">Projeção de Economia</h3>
              <p className="text-slate-400 text-sm">Análise dinâmica de redução de custo em tempo real</p>
            </div>
            <div className="flex items-center space-x-6 mt-4 md:mt-0">
               <div className="flex items-center">
                  <span className="w-3 h-3 rounded-full bg-indigo-500 mr-2"></span>
                  <span className="text-xs text-slate-300 font-medium">Preço Unitário</span>
               </div>
               <div className="flex items-center">
                  <span className="w-3 h-3 rounded-full bg-teal-400 mr-2"></span>
                  <span className="text-xs text-slate-300 font-medium">Economia Total</span>
               </div>
            </div>
          </div>

          <div className="h-[400px] md:h-[500px] w-full relative z-10">
            <ResponsiveContainer width="100%" height="100%">
              <ComposedChart
                data={data}
                margin={{ top: 10, right: 10, bottom: 20, left: 10 }}
                onMouseMove={(e) => {
                  if (e.activeLabel) {
                    setHoverQuantity(Number(e.activeLabel));
                  }
                }}
                onMouseLeave={() => setHoverQuantity(null)}
              >
                <defs>
                  <linearGradient id="colorSavings" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#2dd4bf" stopOpacity={0.3}/>
                    <stop offset="95%" stopColor="#2dd4bf" stopOpacity={0}/>
                  </linearGradient>
                </defs>

                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#334155" opacity={0.5} />
                
                <XAxis 
                  dataKey="quantity" 
                  type="number"
                  domain={[0, 'dataMax']}
                  tick={{ fill: '#94a3b8', fontSize: 12 }}
                  tickLine={false}
                  axisLine={{ stroke: '#475569' }}
                  dy={10}
                  label={{ value: 'Quantidade (Unidades)', position: 'bottom', offset: 0, fill: '#64748b', fontSize: 12 }}
                />
                
                {/* Left Axis: Unit Price - Domain set to make curve look ~45deg down */}
                <YAxis 
                  yAxisId="left" 
                  orientation="left" 
                  tickFormatter={(val) => `R$${val}`}
                  domain={[50, 110]} 
                  tickLine={false}
                  axisLine={false}
                  tick={{ fill: '#818cf8', fontSize: 12 }}
                  width={50}
                />

                {/* Right Axis: Total Savings - Domain set to make curve look ~45deg up */}
                <YAxis 
                  yAxisId="right" 
                  orientation="right" 
                  tickFormatter={(val) => `R$${val}`}
                  tickLine={false}
                  axisLine={false}
                  tick={{ fill: '#2dd4bf', fontSize: 12 }}
                  width={60}
                />

                <Tooltip cursor={{stroke: '#cbd5e1', strokeWidth: 1, strokeDasharray: '4 4'}} content={<CustomTooltip />} />

                <Line
                  yAxisId="left"
                  type="monotone"
                  dataKey="unitPrice"
                  stroke="#818cf8" 
                  strokeWidth={4}
                  dot={false}
                  activeDot={{ r: 6, fill: '#818cf8', stroke: '#1e293b', strokeWidth: 2 }}
                />

                <Area
                  yAxisId="right"
                  type="monotone"
                  dataKey="totalSavings"
                  fill="url(#colorSavings)"
                  stroke="#2dd4bf"
                  strokeWidth={4}
                  activeDot={{ r: 6, fill: '#2dd4bf', stroke: '#1e293b', strokeWidth: 2 }}
                />
                
              </ComposedChart>
            </ResponsiveContainer>
          </div>
          
          <div className="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
             <div className="p-4 bg-teal-500/10 rounded-2xl border border-slate-700/50">
                <p className="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Preço Base</p>
                <p className="text-xl font-bold text-white">R$ {BASE_PRICE}</p>
             </div>
             <div className="p-4 bg-teal-500/10 rounded-2xl border border-slate-700/50">
                <p className="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Início da Otimização</p>
                <p className="text-xl font-bold text-white">10 Unidades</p>
             </div>
             <div className="p-4 bg-teal-500/10 rounded-2xl border  border-slate-700/50">
                <p className="text-xs text-teal-400 font-semibold uppercase tracking-wider mb-1">Eficiência Máxima</p>
                <p className="text-xl font-bold text-white">~35% OFF</p>
             </div>
          </div>
        </div>
      </div>
    </section>
  );
};

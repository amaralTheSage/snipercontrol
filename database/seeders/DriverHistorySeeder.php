<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\DriverHistory;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DriverHistorySeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // HISTÓRICO DO MOTORISTA 1 (Vários veículos ao longo do tempo)
        // ============================================================

        $driver1 = Driver::find(1);

        if (!$driver1) {
            $this->command->warn('Driver 1 não encontrado. Crie drivers primeiro.');
            return;
        }

        $this->command->info("Criando histórico para motorista: {$driver1->name}");

        // Veículo 2: Dirigiu há 5 meses por 1 mês e meio
        $vehicle2Start = now()->subMonths(5);
        $vehicle2End = $vehicle2Start->copy()->addMonths(1)->addDays(15);

        DriverHistory::create([
            'vehicle_id' => 2,
            'driver_id' => 1,
            'started_at' => $vehicle2Start,
            'ended_at' => $vehicle2End,
        ]);

        $this->command->line("  ✓ Veículo 2: " . $vehicle2Start->format('d/m/Y') . " até " . $vehicle2End->format('d/m/Y'));

        // Veículo 3: Dirigiu há 3 meses por 1 mês
        $vehicle3Start = $vehicle2End->copy()->addDays(5);
        $vehicle3End = $vehicle3Start->copy()->addMonth();

        DriverHistory::create([
            'vehicle_id' => 3,
            'driver_id' => 1,
            'started_at' => $vehicle3Start,
            'ended_at' => $vehicle3End,
        ]);

        $this->command->line("  ✓ Veículo 3: " . $vehicle3Start->format('d/m/Y') . " até " . $vehicle3End->format('d/m/Y'));

        // Veículo 1: Está dirigindo atualmente (sem ended_at)
        $vehicle1Start = $vehicle3End->copy()->addDays(2);

        DriverHistory::create([
            'vehicle_id' => 1,
            'driver_id' => 1,
            'started_at' => $vehicle1Start,
            'ended_at' => null, // ATIVO
        ]);

        $this->command->line("  ✓ Veículo 1: " . $vehicle1Start->format('d/m/Y') . " até AGORA (ativo)");

        // ============================================================
        // HISTÓRICO DO VEÍCULO 1 (Vários motoristas ao longo do tempo)
        // ============================================================

        $vehicle1 = Vehicle::find(1);

        if (!$vehicle1) {
            $this->command->warn('Vehicle 1 não encontrado. Crie veículos primeiro.');
            return;
        }

        $this->command->info("\nCriando histórico para veículo: {$vehicle1->plate}");

        // Motorista 2: Dirigiu há 5 meses por 1 mês
        $driver2Start = now()->subMonths(5);
        $driver2End = $driver2Start->copy()->addMonth();

        DriverHistory::create([
            'vehicle_id' => 1,
            'driver_id' => 2,
            'started_at' => $driver2Start,
            'ended_at' => $driver2End,
        ]);

        $this->command->line("  ✓ Motorista 2: " . $driver2Start->format('d/m/Y') . " até " . $driver2End->format('d/m/Y'));

        // Motorista 3: Dirigiu há 3 meses por 20 dias
        $driver3Start = $driver2End->copy()->addDays(7);
        $driver3End = $driver3Start->copy()->addDays(20);

        DriverHistory::create([
            'vehicle_id' => 1,
            'driver_id' => 3,
            'started_at' => $driver3Start,
            'ended_at' => $driver3End,
        ]);

        $this->command->line("  ✓ Motorista 3: " . $driver3Start->format('d/m/Y') . " até " . $driver3End->format('d/m/Y'));

        // Motorista 4: Dirigiu há 2 meses por 1 mês
        $driver4Start = $driver3End->copy()->addDays(3);
        $driver4End = $driver4Start->copy()->addMonth();

        DriverHistory::create([
            'vehicle_id' => 1,
            'driver_id' => 4,
            'started_at' => $driver4Start,
            'ended_at' => $driver4End,
        ]);

        $this->command->line("  ✓ Motorista 4: " . $driver4Start->format('d/m/Y') . " até " . $driver4End->format('d/m/Y'));

        // Motorista 1: Está dirigindo atualmente (mesmo motorista que está ativo acima)
        // Este registro já foi criado acima quando criamos o histórico do motorista 1
        // Então não precisamos criar novamente, apenas atualizar o current_driver_id

        // Atualizar o current_driver_id do veículo 1
        $vehicle1->update(['current_driver_id' => 1]);

        $this->command->line("  ✓ Motorista 1: Atualmente dirigindo veículo 1");


        $this->command->newLine();
        $this->command->info("✅ Seeder concluído!");
        $this->command->line("  • Motorista 1 dirigiu 3 veículos (2, 3, e agora 1)");
        $this->command->line("  • Veículo 1 teve 4 motoristas (2, 3, 4, e agora 1)");
        $this->command->line("  • Estado atual: Motorista 1 está dirigindo Veículo 1");
    }
}

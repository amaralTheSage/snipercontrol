<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Warning;
use Illuminate\Database\Seeder;

class WarningSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = Driver::all();
        $vehicles = Vehicle::all();
        $users = User::all();

        if ($drivers->isEmpty() || $vehicles->isEmpty()) {
            $this->command->warn('Please seed Drivers and Vehicles first!');

            return;
        }

        $warnings = [
            // Route Diversions
            [
                'type' => 'route_diversion',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,

                'latitude' => -23.6280,
                'longitude' => -46.7030,
                'severity' => 'low',
                'occurred_at' => now()->subDays(5)->setTime(14, 30),
                'resolved_at' => now()->subDays(4)->setTime(9, 15),
                'resolved_by' => $users->random()->id,
                'resolution_notes' => 'Verificado com o motorista. Desvio justificado devido ao trânsito intenso na Via Expressa. Procedimento correto.',
            ],
            [
                'type' => 'route_diversion',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Desvio de rota detectado. Veículo saiu da rota programada por mais de 20 minutos.',
                'location' => 'Rod. Presidente Dutra, Km 225 - Guarulhos, SP',
                'latitude' => -23.4356,
                'longitude' => -46.4731,
                'severity' => 'medium',
                'occurred_at' => now()->subDays(3)->setTime(22, 45),
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null,
            ],
            [
                'type' => 'route_diversion',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Alerta de desvio significativo da rota. Motorista dirigiu para área não autorizada.',
                'location' => 'Av. Paulista, 1578 - Bela Vista, São Paulo, SP',
                'latitude' => -23.5613,
                'longitude' => -46.6563,
                'severity' => 'high',
                'occurred_at' => now()->subHours(6),
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null,
            ],

            // Cargo Theft
            [
                'type' => 'cargo_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Tentativa de roubo de carga relatada. Motorista acionou botão de pânico. Polícia acionada no local. Carga recuperada integralmente.',
                'location' => 'Rod. Régis Bittencourt, Km 280 - Embu das Artes, SP',
                'latitude' => -23.6489,
                'longitude' => -46.8524,
                'severity' => 'high',
                'occurred_at' => now()->subDays(10)->setTime(3, 20),
                'resolved_at' => now()->subDays(10)->setTime(10, 30),
                'resolved_by' => $users->random()->id,
                'resolution_notes' => 'Boletim de ocorrência registrado. Carga recuperada. Motorista recebeu suporte psicológico. Seguradora acionada para cobertura de danos ao veículo.',
            ],
            [
                'type' => 'cargo_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Roubo de carga confirmado. Veículo encontrado abandonado sem a carga. Motorista ileso.',
                'location' => 'Av. do Estado, 5533 - Ipiranga, São Paulo, SP',
                'latitude' => -23.5876,
                'longitude' => -46.6109,
                'severity' => 'high',
                'occurred_at' => now()->subDays(7)->setTime(23, 15),
                'resolved_at' => now()->subDays(6)->setTime(14, 0),
                'resolved_by' => $users->random()->id,
                'resolution_notes' => 'B.O. registrado sob nº 2024/123456. Carga avaliada em R$ 85.000. Processo de indenização iniciado com a seguradora. Veículo recuperado com danos leves.',
            ],
            [
                'type' => 'cargo_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Suspeita de roubo. Veículo parado em local não autorizado por tempo prolongado. Sem contato com motorista.',
                'location' => 'Rod. Anchieta, Km 23 - São Bernardo do Campo, SP',
                'latitude' => -23.7276,
                'longitude' => -46.5731,
                'severity' => 'high',
                'occurred_at' => now()->subHours(12),
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null,
            ],

            // Fuel Theft
            [
                'type' => 'fuel_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Queda abrupta no nível de combustível detectada. Aproximadamente 200 litros de diesel furtados durante a noite.',
                'location' => 'Av. Cupecê, 3959 - Jardim da Saúde, São Paulo, SP',
                'latitude' => -23.6247,
                'longitude' => -46.6428,
                'severity' => 'medium',
                'occurred_at' => now()->subDays(2)->setTime(2, 30),
                'resolved_at' => now()->subDays(1)->setTime(16, 45),
                'resolved_by' => $users->random()->id,
                'resolution_notes' => 'Combustível reposto. Orientado motorista a não estacionar em locais isolados. Sistema de alarme do tanque verificado e reforçado.',
            ],
            [
                'type' => 'fuel_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Sensor de combustível indicando vazamento ou furto. Nível caiu de 80% para 20% em 30 minutos sem abastecimento registrado.',
                'location' => 'R. Vergueiro, 3185 - Vila Mariana, São Paulo, SP',
                'latitude' => -23.5989,
                'longitude' => -46.6392,
                'severity' => 'medium',
                'occurred_at' => now()->subDays(1)->setTime(21, 0),
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null,
            ],
            [
                'type' => 'fuel_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Alerta de furto de combustível. Tanque violado durante período noturno.',
                'location' => 'Av. Sapopemba, 9064 - Sapopemba, São Paulo, SP',
                'latitude' => -23.5969,
                'longitude' => -46.4911,
                'severity' => 'low',
                'occurred_at' => now()->subHours(18),
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null,
            ],

            // Additional diverse scenarios
            [
                'type' => 'route_diversion',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Motorista precisou desviar devido a acidente na pista principal. Rota alternativa aprovada pela central.',
                'location' => 'Av. Bandeirantes, 2500 - Ipiranga, São Paulo, SP',
                'latitude' => -23.5825,
                'longitude' => -46.6064,
                'severity' => 'low',
                'occurred_at' => now()->subDays(8)->setTime(11, 15),
                'resolved_at' => now()->subDays(8)->setTime(12, 30),
                'resolved_by' => $users->random()->id,
                'resolution_notes' => 'Desvio autorizado via central de operações. Tempo de viagem não comprometido significativamente.',
            ],
            [
                'type' => 'cargo_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Falso frete detectado. Tentativa de roubo através de golpe de carga falsa.',
                'location' => 'R. Dr. João Ribeiro, 304 - Penha, São Paulo, SP',
                'latitude' => -23.5283,
                'longitude' => -46.5411,
                'severity' => 'high',
                'occurred_at' => now()->subDays(15)->setTime(16, 0),
                'resolved_at' => now()->subDays(14)->setTime(10, 0),
                'resolved_by' => $users->random()->id,
                'resolution_notes' => 'Golpe identificado antes da carga ser liberada. Contato realizado com a polícia. Empresa fraudadora investigada.',
            ],
            [
                'type' => 'fuel_theft',
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
                'description' => 'Múltiplas tentativas de acesso ao tanque de combustível detectadas pelos sensores.',
                'location' => 'Av. Aricanduva, 5555 - Vila Formosa, São Paulo, SP',
                'latitude' => -23.5611,
                'longitude' => -46.5136,
                'severity' => 'high',
                'occurred_at' => now()->subHours(3),
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null,
            ],
        ];

        foreach ($warnings as $warning) {
            Warning::create($warning);
        }

        $this->command->info('Created '.count($warnings).' warnings successfully!');
    }
}

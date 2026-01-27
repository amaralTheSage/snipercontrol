<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'teste',
        //     'email' => 'teste@gmail.com',
        //     'password' => Hash::make('senha123'),
        // ]);


        // User::factory()->create([
        //     'name' => 'gabriel',
        //     'email' => 'gabriel@g',
        //     'password' => Hash::make('senha123'),
        // ]);

        // Drivers
        $drivers = collect([
            [
                'name' => 'João Silva',
                'cpf' => '12345678901',
                'phone' => '11999999999',
            ],
            [
                'name' => 'Carlos Souza',
                'cpf' => '98765432100',
                'phone' => '11988888888',
            ],
        ])->map(fn($data) => Driver::firstOrCreate([
            ...$data,
            'status' => 'active',
        ]));

        // Vehicles
        $vehicles = collect([
            [
                'plate' => 'ABC1D23',
                'model' => 'Volvo FH',
                'year' => 2022,
                'type' => 'truck',
            ],
            [
                'plate' => 'XYZ9K88',
                'model' => 'Sprinter',
                'year' => 2021,
                'type' => 'van',
            ],
        ])->map(fn($data) => Vehicle::firstOrCreate([
            ...$data,
            'status' => 'active',
            'current_speed' => rand(0, 90),
            'fuel_level' => rand(20, 100),
            'ignition_on' => (bool) rand(0, 1),
            'relay_enabled' => true,
            'last_latitude' => -23.55 + rand(-100, 100) / 1000,
            'last_longitude' => -46.63 + rand(-100, 100) / 1000,
            'last_update_at' => now(),
        ]));

        // Devices + vínculos
        $vehicles->each(function (Vehicle $vehicle, int $index) use ($drivers) {
            $device = Device::firstOrCreate([
                'serial' => 'DEV-' . strtoupper(uniqid()),
                'vehicle_id' => $vehicle->id,
                'status' => 'online',
                'last_communication_at' => Carbon::now()->subMinutes(rand(1, 5)),
            ]);

            $driver = $drivers[$index % $drivers->count()];

            // Vincula tudo
            $vehicle->update([
                'current_driver_id' => $driver->id,
            ]);
        });
    }
}

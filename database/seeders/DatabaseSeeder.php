<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Device;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $createAvatar = function () {
            $filename = Str::random(10) . '.jpg';
            $imageData = file_get_contents('https://picsum.photos/300/300');
            Storage::disk('public')->put("avatars/{$filename}", $imageData);
            return "avatars/{$filename}";
        };

        $user = User::factory()->create([
            'name' => 'teste',
            'email' => 't@t',
            'avatar' => $createAvatar(),
            'role' => UserRole::ADMIN,
            'password' => Hash::make('t'),
        ]);

        $driversData = [
            ['name' => 'João Silva', 'cpf' => '12345678901', 'phone' => '11999999999'],
            ['name' => 'Carlos Souza', 'cpf' => '98765432100', 'phone' => '11988888888'],
            ['name' => 'Marcos Lima', 'cpf' => '11122233344', 'phone' => '11977777777'],
            ['name' => 'Pedro Santos', 'cpf' => '55566677788', 'phone' => '11966666666'],
            ['name' => 'Rafael Costa', 'cpf' => '99988877766', 'phone' => '11955555555'],
            ['name' => 'Lucas Rocha', 'cpf' => '22233344455', 'phone' => '11944444444'],
            ['name' => 'André Pires', 'cpf' => '66677788899', 'phone' => '11933333333'],
        ];

        $vehicleTemplates = [
            ['model' => 'Volvo FH', 'type' => 'truck'],
            ['model' => 'Scania R450', 'type' => 'truck'],
            ['model' => 'Mercedes Actros', 'type' => 'truck'],
            ['model' => 'Sprinter', 'type' => 'van'],
            ['model' => 'Iveco Daily', 'type' => 'van'],
            ['model' => 'Fiat Fiorino', 'type' => 'car'],
        ];

        // device fixo (1º veículo)
        $fixedDevice = Device::firstOrCreate(
            ['mac_address' => 'AA:BB:CC:DD:EE:FF'],
            [
                'status' => 'online',
                'company_id' => $user->id,
            ]
        );

        $index = 0;

        foreach ($driversData as $data) {
            $driver = Driver::firstOrCreate(
                ['cpf' => $data['cpf']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'avatar' => $createAvatar(),
                    'status' => 'active',
                    'company_id' => $user->id,
                ]
            );

            if ($driver->currentVehicle) {
                continue;
            }

            $template = $vehicleTemplates[array_rand($vehicleTemplates)];

            $vehicle = Vehicle::create([
                'plate' => strtoupper(Str::random(3)) . rand(1000, 9999),
                'model' => $template['model'],
                'year' => rand(2019, 2024),
                'type' => $template['type'],
                'status' => 'active',
                'current_driver_id' => $driver->id,
                'current_speed' => rand(0, 110),
                'fuel_level' => rand(20, 100),
                'ignition_on' => (bool) rand(0, 1),
                'last_latitude' => -23.55 + rand(-500, 500) / 10000,
                'last_longitude' => -46.63 + rand(-500, 500) / 10000,
                'last_update_at' => now(),
                'company_id' => $user->id,
            ]);

            if ($index === 0) {
                // associa o device fixo
                $fixedDevice->update(['vehicle_id' => $vehicle->id]);
            } else {
                // cria device único
                Device::create([
                    'vehicle_id' => $vehicle->id,
                    'mac_address' => strtoupper(implode(':', str_split(Str::random(12), 2))),
                    'status' => 'online',
                    'company_id' => $user->id,
                ]);
            }

            $index++;
        }
    }
}

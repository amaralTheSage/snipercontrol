<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class RelayCommandService
{
    public function sendCommand(string $deviceId, string $command): bool
    {
        $topic = "vehicle/cmd";

        try {
            $mqtt = new MqttClient(
                config('mqtt.broker'),
                config('mqtt.port'),
                'laravel-' . uniqid()
            );

            $connectionSettings = (new ConnectionSettings)
                ->setUsername(config('mqtt.username'))
                ->setPassword(config('mqtt.password'))
                ->setKeepAliveInterval(60);

            $mqtt->connect($connectionSettings, true);
            $mqtt->publish($topic, strtoupper($command), 0);
            $mqtt->disconnect();

            return true;
        } catch (\Exception $e) {
            Log::error("MQTT Command Failed: " . $e->getMessage());
            return false;
        }
    }

    public function cutoff(string $deviceId): bool
    {
        return $this->sendCommand($deviceId, 'CUTOFF');
    }

    public function restore(string $deviceId): bool
    {
        return $this->sendCommand($deviceId, 'RESTORE');
    }
}

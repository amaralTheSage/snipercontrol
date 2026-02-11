<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class RelayController extends Controller
{
    private function getMqttClient()
    {
        $server = env('MQTT_BROKER', 'localhost');
        $port = env('MQTT_PORT', 1883);
        $clientId = 'laravel-' . uniqid();

        $client = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setUsername(env('MQTT_USER'))
            ->setPassword(env('MQTT_PASSWORD'))
            ->setKeepAliveInterval(60);

        $client->connect($connectionSettings, true);

        return $client;
    }

    public function cutoff(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        $device = Device::findOrFail($request->device_id)->mac_address;
        $topic = "vehicle/" . $device . "/cmd";

        FacadesLog::info("Sending CUTOFF command to topic: " . $topic);

        try {
            $mqtt = $this->getMqttClient();
            $mqtt->publish($topic, 'CUTOFF', 0);
            $mqtt->disconnect();

            // // Log to database
            // DB::table('relay_commands')->insert([
            //     'device_id' => $deviceId,
            //     'command' => 'CUTOFF',
            //     'user_id' => auth()->id(),
            //     'created_at' => now()
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Engine cutoff command sent'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send command: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        $device = Device::findOrFail($request->device_id)->mac_address;
        $topic = "vehicle/" . $device . "/cmd";

        try {
            $mqtt = $this->getMqttClient();
            $mqtt->publish($topic, 'RESTORE', 0);
            $mqtt->disconnect();

            // \DB::table('relay_commands')->insert([
            //     'device_id' => $deviceId,
            //     'command' => 'RESTORE',
            //     'user_id' => auth()->id(),
            //     'created_at' => now()
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Engine restore command sent'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send command: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatus(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        $device = Device::findOrFail($request->device_id)->mac_address;
        $topic = "vehicle/" . $device . "/cmd";

        try {
            $mqtt = $this->getMqttClient();
            $mqtt->publish($topic, 'STATUS', 0);
            $mqtt->disconnect();

            return response()->json([
                'success' => true,
                'message' => 'Status request sent'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send command: ' . $e->getMessage()
            ], 500);
        }
    }
}

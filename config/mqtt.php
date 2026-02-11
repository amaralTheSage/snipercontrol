<?php
// config/mqtt.php

return [
    'broker' => env('MQTT_BROKER'),
    'port' => (int) env('MQTT_PORT', 1883),
    'username' => env('MQTT_USER', 'root'),
    'password' => env('MQTT_PASSWORD', 'promoki123'),
];

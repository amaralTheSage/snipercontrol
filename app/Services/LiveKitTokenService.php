<?php

namespace App\Services;

use Firebase\JWT\JWT;

class LiveKitTokenService
{
    public function makeToken(string $identity, string $room, array $grants = [])
    {
        $now = time();

        $payload = [
            'iss' => config('livekit.key'),
            'sub' => $identity,
            'nbf' => $now,
            'exp' => $now + 3600,
            'video' => array_merge([
                'roomJoin' => true,
                'room' => $room,
            ], $grants),
        ];

        return JWT::encode($payload, config('livekit.secret'), 'HS256');
    }
}

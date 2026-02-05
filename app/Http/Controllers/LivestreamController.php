<?php


namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Services\LiveKitTokenService;
use Illuminate\Support\Facades\Route;
use Livekit\AccessToken;

class LivestreamController extends Controller
{
    // public function viewerToken(Request $request, LiveKitTokenService $tokens)
    // {
    //     Route::post('/livekit/viewer-token', function (Request $request) {

    //         $deviceId = $request->device_id;

    //         $token = new AccessToken(
    //             config('services.livekit.key'),
    //             config('services.livekit.secret')
    //         );

    //         $token->setIdentity("viewer-" . auth()->id());

    //         $token->setVideoGrant([
    //             'roomJoin' => true,
    //             'room' => "device-{$deviceId}",
    //             'canPublish' => false,
    //             'canSubscribe' => true,
    //         ]);

    //         return [
    //             'token' => $token->toJwt(),
    //             'url' => config('services.livekit.url'),
    //         ];
    //     });
    // }

    // public function deviceToken(Request $request, LiveKitTokenService $tokens)
    // {

    //     $deviceId = $request->device_id;

    //     $token = new AccessToken(
    //         config('services.livekit.key'),
    //         config('services.livekit.secret')
    //     );

    //     $token->setIdentity("device-{$deviceId}");
    //     $token->setName("device-{$deviceId}");

    //     $token->setVideoGrant([
    //         'roomJoin' => true,
    //         'room' => "device-{$deviceId}",
    //         'canPublish' => true,
    //         'canSubscribe' => false,
    //     ]);

    //     return [
    //         'token' => $token->toJwt(),
    //         'url' => config('services.livekit.url'),
    //     ];
    // }
}

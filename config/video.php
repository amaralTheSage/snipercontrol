<?php

return [
    // Storage configuration
    'storage_disk' => env('VIDEO_STORAGE_DISK', 'local'),
    'max_upload_size' => env('VIDEO_MAX_UPLOAD_SIZE', 512000), // KB

    // Retention policies
    'retention_days' => 30, // mantém gravado por 30 dias

    // Auto-archive after retention period
    'auto_archive' => env('VIDEO_AUTO_ARCHIVE', true),

    // Streaming configuration
    'streaming' => [
        'enabled' => env('VIDEO_STREAMING_ENABLED', true),
        'protocol' => env('VIDEO_STREAMING_PROTOCOL', 'hls'), // hls, dash, rtmp
        'quality_levels' => ['360p', '720p', '1080p'],
    ],

    // Live streaming
    'live_streaming' => [
        'enabled' => env('LIVE_STREAMING_ENABLED', true),
        'max_concurrent' => env('LIVE_STREAMING_MAX_CONCURRENT', 10),
        'timeout_seconds' => env('LIVE_STREAMING_TIMEOUT', 300), // 5 minutes
    ],
];

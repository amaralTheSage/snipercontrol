<?php

namespace App\Filament\Widgets;

use EduardoRibeiroDev\FilamentLeaflet\Widgets\MapWidget;
use EduardoRibeiroDev\FilamentLeaflet\Support\Markers\Marker;

class DriverMapWidget extends MapWidget
{

    // Map heading
    protected static ?string $heading = 'Store Locations';

    // Center coordinates [latitude, longitude]
    protected static array $mapCenter = [-14.235, -51.9253];

    // Initial zoom level (1-18)
    protected static int $defaultZoom = 4;

    // Map height in pixels
    protected static int $mapHeight = 600;

    // Zoom configuration
    protected static int $maxZoom = 18;
    protected static int $minZoom = 2;

    protected function getMarkers(): array
    {
        return [
            Marker::make(-23.5505, -46.6333)
                ->title('São Paulo')
                ->popupContent('The largest city in Brazil'),
        ];
    }
}

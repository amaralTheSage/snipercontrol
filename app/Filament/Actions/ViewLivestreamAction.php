<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class ViewLivestreamAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'viewLivestream';
    }

    protected function setup(): void
    {
        parent::setUp();

        $this->label('Ver Transmissão ao Vivo')
            ->color('success')
            ->modal(false) // IMPORTANT — you're using your own modal
            ->action(function ($livewire, $arguments, $record) {

                $livewire->dispatch('open-livestream', [
                    'device_id' => $record->device->id,
                    'url' => $arguments['url'] ?? '',
                    'title' => $arguments['title'] ?? 'Transmissão ao Vivo',
                    'description' => $arguments['description'] ?? '',
                    'driver' => $record->currentDriver->name,
                    'vehicle' => $record->plate,
                    'startedAt' => $arguments['startedAt'] ?? now()->format('H:i:s'),
                    'viewers' => $arguments['viewers'] ?? 1,
                ]);
            });
    }

    /**
     * Set the stream URL
     */
    public function streamUrl(string $url): static
    {
        $this->arguments(['url' => $url]);

        return $this;
    }

    /**
     * Set the stream title
     */
    public function streamTitle(string $title): static
    {
        $this->arguments(['title' => $title]);

        return $this;
    }

    /**
     * Set the stream description
     */
    public function streamDescription(string $description): static
    {
        $this->arguments(['description' => $description]);

        return $this;
    }

    /**
     * Set the driver name
     */
    public function driver(?string $driver): static
    {
        $this->arguments(['driver' => $driver]);

        return $this;
    }

    /**
     * Set the vehicle info
     */
    public function vehicle(?string $vehicle): static
    {
        $this->arguments(['vehicle' => $vehicle]);

        return $this;
    }

    /**
     * Set all stream data at once
     */
    public function streamData(array $data): static
    {
        $this->arguments($data);

        return $this;
    }
}

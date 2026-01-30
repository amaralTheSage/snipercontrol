<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class RoleSwitcher extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.role-switcher';

    protected int|string|array $columnSpan = '1';

    public ?array $data = [];

    public static function canView(): bool
    {
        return App::environment('local');
    }

    public function mount(): void
    {
        // Get the actual value from the logged-in user
        $this->form->fill([
            'role' => Auth::user()->role?->value ?? Auth::user()->role,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('role')
                    ->label('Switch de Roles')
                    ->options(UserRole::class) // Filament handles enum cases automatically
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $user = Auth::user();

                        // Update the database column
                        $user->update(['role' => $state]);

                        // Refresh the page to apply new permissions/UI changes
                        return redirect(request()->header('Referer'));
                    })
                    ->helperText('⚠️ Somente para Desenvolvimento'),
            ])
            ->statePath('data');
    }
}

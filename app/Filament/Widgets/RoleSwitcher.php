<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleSwitcher extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.role-switcher';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'role' => Auth::user()->roles->first()?->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('role')
                    ->label('Development Role Switcher')
                    ->options(Role::all()->pluck('name', 'name'))
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $user = Auth::user();

                        // Remove all current roles
                        $user->syncRoles([]);

                        // Assign the selected role
                        if ($state) {
                            $user->assignRole($state);
                        }

                        // Refresh the page to apply new permissions
                        redirect()->to(request()->header('Referer'));
                    })
                    ->helperText('⚠️ Development only - Switch roles instantly'),
            ])
            ->statePath('data');
    }
}

<?php

namespace App\Providers\Filament;

use App\Filament\Pages;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Auth\EditProfile;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin;
use BezhanSalleh\FilamentShield\Resources\RoleResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $userPolicy = new UserPolicy();
        $rolePolicy = new RolePolicy();

        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
//                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
            ])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa()
            ->unsavedChangesAlerts()
            ->topNavigation()
            ->brandName(
                setting('general.brand_name') ?? config('app.name')
            )
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentSettingsPlugin::make()
                    ->pages([
                        Pages\Settings::class,
                    ])
            ])
            ->profile()
            ->userMenuItems([
                MenuItem::make()
                    ->label('Users')
                    ->url(fn(): string => ListUsers::getUrl())
                    ->icon('heroicon-o-users')
                    ->visible(fn(): bool => $userPolicy->viewAny(auth()->user())),
                MenuItem::make()
                    ->label('Roles')
                    ->url(fn(): string => RoleResource::getUrl())
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn(): bool => $rolePolicy->viewAny(auth()->user())),
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn(): string => Pages\Settings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->visible(fn(): bool => Pages\Settings::canAccess()),
            ]);

        if (setting('general.brand_logo')) {
            $panel
                ->brandLogo(
                    asset(
                        Storage::disk('public')
                            ->url(setting('general.brand_logo'))
                    )
                )
                ->brandLogoHeight('2.5rem');
        }

        return $panel;
    }
}

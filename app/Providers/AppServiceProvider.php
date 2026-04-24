<?php

namespace App\Providers;

use App\Models\ApplicationSetting;
use App\Services\Mail\SmtpConfigFromApplicationSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        SmtpConfigFromApplicationSettings::apply();

        View::share(
            'cubaAsset',
            fn (string $path) => asset(config('cuba.assets_path').'/'.ltrim($path, '/')),
        );

        View::composer('*', function (\Illuminate\View\View $view): void {
            $applicationSetting = Schema::hasTable('application_settings')
                ? ApplicationSetting::instance()
                : new ApplicationSetting;
            $resolvedHeader = $applicationSetting->displayHeaderLogoUrl();
            $base = config('cuba.assets_path');
            $themeAsset = fn (string $path) => asset($base.'/'.ltrim($path, '/'));

            $data = [
                'applicationSetting' => $applicationSetting,
                '_brandHeaderLight' => $resolvedHeader,
                '_brandHeaderDark' => $resolvedHeader,
                '_brandHeaderIcon' => $resolvedHeader,
            ];

            $view->with($data);
        });
    }
}

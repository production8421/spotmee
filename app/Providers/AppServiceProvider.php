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
        $this->bootstrapFilesystemTempEnvironment();
    }

    /**
     * Blade compilation uses Filesystem::replace() → tempnam(dirname(compiled_view), …).
     * If that directory is missing or not writable, PHP falls back to the OS temp dir and may emit
     * "tempnam(): file created in the system's temporary directory", which becomes ErrorException when
     * warnings are promoted. Point TMPDIR at storage/framework/tmp so fallbacks stay inside the app.
     */
    private function bootstrapFilesystemTempEnvironment(): void
    {
        $dirs = [
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            storage_path('framework/tmp'),
        ];

        foreach ($dirs as $dir) {
            if (! is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }

        $tmp = storage_path('framework/tmp');
        if (! is_dir($tmp) || ! is_writable($tmp)) {
            return;
        }

        // TMPDIR is read by PHP's sys_get_temp_dir() on Unix; TEMP/TMP on Windows in some builds.
        putenv('TMPDIR='.$tmp);
        $_ENV['TMPDIR'] = $tmp;
        $_SERVER['TMPDIR'] = $tmp;
        if (DIRECTORY_SEPARATOR === '\\') {
            putenv('TMP='.$tmp);
            putenv('TEMP='.$tmp);
            $_ENV['TMP'] = $tmp;
            $_ENV['TEMP'] = $tmp;
        }
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

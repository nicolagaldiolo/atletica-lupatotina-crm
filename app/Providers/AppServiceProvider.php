<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Blade::directive('money', function ($amount) {
            return "<?php echo number_format($amount, 2, ',', '.') . ' &euro;'; ?>";
        });

        Blade::directive('value', function ($amount) {
            return "<?php echo number_format($amount, 2, ',', '.'); ?>";
        });

        Blade::directive('date', function ($date) {
            return "<?php echo ($date)->format('d/m/Y'); ?>";
        });
    }
}

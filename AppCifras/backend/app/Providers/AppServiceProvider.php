<?php

namespace App\Providers;

use App\Models\Musica;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        Route::bind('musica', function (string $value): Musica {
            $query = Musica::query();
            if (auth()->check()) {
                $query->where('usuario_criador_id', auth()->id());
            }
            if (is_numeric($value)) {
                return $query->where('id', (int) $value)->firstOrFail();
            }
            return $query->where('slug', $value)->firstOrFail();
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Caminho da rota "home" após autenticação (se aplicável)
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Registrar serviços de roteamento.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));


            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}

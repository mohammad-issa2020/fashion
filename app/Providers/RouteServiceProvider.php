<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';
    public const ADMIN = '/admin/admin_profile';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminApiRoutes();

        $this->mapUserApiRoutes();

        $this->mapExpertApiRoutes();

        $this->mapCompanyApiRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapAdminApiRoutes()
    {
        Route::prefix('admin-api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin_api.php'));
    }

    protected function mapUserApiRoutes()
    {
        Route::prefix('user-api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/user_api.php'));
    }

    protected function mapExpertApiRoutes()
    {
        Route::prefix('expert-api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/expert_api.php'));
    }

    protected function mapCompanyApiRoutes()
    {
        Route::prefix('company-api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/company_api.php'));
    }

    
}

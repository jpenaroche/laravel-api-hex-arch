<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'User' => 'App\Src\Domain\Models\User'
        ]);

        //Registro my custom resource customization
        $api_route_provider = new \App\Customizations\ResourceProvider($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($api_route_provider) {
            return $api_route_provider;
        });

        //Metodo paginate para collections
        if (!Collection::hasMacro('paginate')) {

            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage)->values(), $this->count(), $perPage, $page, $options))
                        ->withPath('');
                });
        }

        //Metodo orderBy para collections
        if (!Collection::hasMacro('orderBy')) {

            Collection::macro('orderBy', function ($needle, $orientation = null) {
                if ($orientation)
                    if ($orientation == 'desc')
                        return $this->sortByDesc($needle);
                return $this->sortBy($needle);
            });
        }

        //Metodo para crear colecciones recursivas en arrays de mas de un nivel
        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value)) {
                    return collect($value)->recursive();
                }
                if (is_object($value)) {
                    return collect($value)->recursive();
                }

                return $value;
            });
        });

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

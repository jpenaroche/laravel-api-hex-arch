<?php

namespace App\Http\Controllers;

use App\Src\Application\Bus\SimpleBusCommand;
use App\Src\Application\Utils\Inflector;
use Illuminate\Container\Container;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $container;
    /**
     * Controller constructor.
     */
    public function __construct()
    {
//        Aqui se inyectan en el IoC container las dependendencias q se usaran en la capa aplicacion

        $this->container = new Container();
        $this->container->singleton('App\Src\Contracts\Transaction','App\Src\Framework\LaravelTransaction');
//        $this->container->bind('App\Src\Contracts\CacheInterface','App\Src\Framework\LaravelCache');
        $this->container->singleton('App\Src\Contracts\RepositoryInterface','App\Src\Framework\EloquentRepository');
        $this->container->singleton('App\Src\Contracts\Mediable','App\Src\Framework\LaravelMediable');
        $this->container->singleton('App\Src\Contracts\Dispatcher','App\Src\Framework\LaravelDispatcher');
        $this->bus = new SimpleBusCommand($this->container, Inflector::singleton());
    }
}

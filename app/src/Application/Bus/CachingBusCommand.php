<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/19/19
 * Time: 3:28 PM
 */

namespace App\Src\Application\Bus;


use App\Src\Application\Utils\Inflector;
use App\Src\Contracts\CommandBusInterface;
use Illuminate\Container\Container;

class CachingBusCommand implements CommandBusInterface
{

    /**
     * SimpleBusCommand constructor.
     */
    public function __construct(Container $container,Inflector $inflector)
    {
        $this->container = $container;
        $this->inflector = $inflector;
    }

    public function execute($command)
    {
       return $this->resolveHandler($command)->handle($command);
    }

    private function resolveHandler($command){
        return $this->container->make($this->inflector->getHandler($command));
    }
}
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

class ValidatorBusCommand implements CommandBusInterface
{

    /**
     * SimpleBusCommand constructor.
     */
        public function __construct(Container $container,Inflector $inflector,CommandBusInterface $bus)
    {
        $this->container = $container;
        $this->inflector = $inflector;
        $this->next = $bus;
    }

    public function execute($command)
    {
        $this->resolveValidator($command)->validate($command);
        return $this->next->execute($command);
    }

    private function resolveValidator($command){
        return $this->container->make($this->inflector->getValidator($command));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/19/19
 * Time: 3:28 PM
 */

namespace App\Src\Application\Handlers;


use App\Src\Contracts\Dispatcher;
use App\Src\Contracts\HandlerInterface;
use App\Src\Contracts\RepositoryInterface;
use App\Src\Contracts\Transaction;
use App\Src\Domain\Repositories\PermissionRepository;
use App\Src\Domain\Repositories\RoleRepository;

class PermissionHandler extends BaseHandler implements HandlerInterface
{
    /**
     * CRUDHandler constructor.
     */
    public function __construct(Transaction $transaction, RepositoryInterface $base, Dispatcher $dispatcher)
    {
        //Obtengo la especificacion de repositorio (Eloquent || Doctrine ...) desde
        // el IoC del framework y lo guardo en la var $base
        $this->dispatcher = $dispatcher;
        parent::__construct($transaction, PermissionRepository::singleton(60, $base), $dispatcher);
    }

    public function handle($command)
    {
        $this->setCommand($command);

        return $this->{$command->getOperation()}();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/19/19
 * Time: 3:28 PM
 */

namespace App\Src\Application\Handlers;


use App\Events\ModelCreated;
use App\Events\ModelDeleted;
use App\Events\ModelUpdated;
use App\Exceptions\DomainException;
use App\Src\Application\Utils\FileManager;
use App\Src\Contracts\Dispatcher;
use App\Src\Contracts\HandlerInterface;
use App\Src\Contracts\Mediable;
use App\Src\Contracts\RepositoryInterface;
use App\Src\Contracts\Transaction;
use App\Src\Domain\Repositories\UserRepository;
use App\Src\Framework\LaravelMediable;

class UserHandler extends BaseHandler implements HandlerInterface
{
    /**
     * CRUDHandler constructor.
     */
    public function __construct(Transaction $transaction, RepositoryInterface $base, Mediable $mediable, Dispatcher $dispatcher)
    {
        //Obtengo la especificacion de repositorio (Eloquent || Doctrine ...) desde
        // el IoC del framework y lo guardo en la var $base
        $this->dispatcher = $dispatcher;
        parent::__construct($transaction, UserRepository::singleton(60, $base), $dispatcher);
        $this->setMediable($mediable);
    }

    public function handle($command)
    {
        $this->setCommand($command);

        return $this->{$command->getOperation()}();
    }

    public function do(){

    }
}
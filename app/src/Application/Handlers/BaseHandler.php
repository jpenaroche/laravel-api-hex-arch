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
use App\Events\ModelToggled;
use App\Events\ModelUpdated;
use App\Src\Application\Utils\FileManager;
use App\Src\Contracts\CRUDOperations;
use App\Src\Domain\Models\User;

class BaseHandler implements CRUDOperations
{
    use FileManager;

    protected $command;
    protected $repository;
    protected $dispatcher;
    protected $mediable;

    /**
     * CRUDHandler constructor.
     */
    public function __construct($transaction, $repository, $dispatcher)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->dispatcher = $dispatcher;
    }

    public function show()
    {
        return $this->repository
            ->with($this->repository->getModel()::$relationships)
            ->find($this->command->getAttributes()['id']);
    }

    public function list()
    {
        return $this->repository
            ->with($this->repository->getModel()::$relationships)
            ->get();
    }

    public function create($load = true, $dispatch = true)
    {
        $result = $this->transaction->execute(
            function () use ($load) {
                $this->uploadFiles();
                return $this->repository->save($this->command->getAttributes(), $load);
            }
        );

        //Emito el model creado por websockets
        if ($dispatch)
            $this->dispatcher->broadcast(new ModelCreated(class_basename($this->repository->getModel()), $result), true);

        return $result;
    }

    public function update($load = true, $dispatch = true)
    {
        $result = $this->transaction->execute(
            function () use ($load) {
                $this->uploadFiles();
                return $this->repository->update($this->command->id, $this->command->getAttributes(), $load);
            }
        );

        //Emito el model actualizado por websockets
        if ($dispatch)
            $this->dispatcher->broadcast(new ModelUpdated(class_basename($this->repository->getModel()), $result->id, $result), true);

        return $result;
    }

    public function delete($dispatch = true)
    {
        $result = $this->transaction->execute(
            function () {
                return $this->repository->delete($this->command->getAttributes()['id']);
            }
        );

        //Emito el model eliminado por websockets
        if ($dispatch)
            $this->dispatcher->broadcast(new ModelDeleted(class_basename($this->repository->getModel()), $result->id), true);

        return $result;
    }

    public function toggle($dispatch = true)
    {
        $result = $this->transaction->execute(function () {
            return $this->repository->toggle($this->command->field, $this->command->ids);
        });

        //Emito el model actualizado en $field por websockets
        //$result = [$field=>$id]
        if ($dispatch)
            $this->dispatcher->broadcast(new ModelToggled(class_basename($this->repository->getModel()), $this->command->field, $result), true);

        return $result;
    }

    public function medias()
    {
        return $this->repository->loadMedias($this->command->id, $this->command->tags, $this->command->match);
    }

    public function uploadFiles()
    {
        if (isset($this->command->getAttributes()['medias'])) {
            $medias = $this->command->medias;
            $loaded = $this->transaction->execute(function () use ($medias) {
                return $this->uploadMany($medias);
            });

            $this->command->setMedias($medias + compact('loaded'));
        }
    }

    /**
     * @param mixed $command
     */
    public function saveOrder($dispatch = true)
    {
        $pos = 0;
        foreach ($this->command->items as $id) {
            $this->transaction->execute(
                function () use ($id, &$pos) {
                    return $this->repository->update($id, ['pos' => ++$pos], false);
                }
            );
        }

        $result = $this->repository->all();
//        if ($dispatch)
//            $this->dispatcher->broadcast(new ModelToggled(class_basename($this->repository->getModel()), 'pos', $result), true);

        return $result;
    }

    /**
     * @param mixed $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @param mixed $mediable
     */
    public function setMediable($mediable)
    {
        $this->mediable = $mediable;
    }

}
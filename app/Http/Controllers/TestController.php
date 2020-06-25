<?php

namespace App\Http\Controllers;

use App\Exceptions\DomainException;
use App\Exceptions\TransactionException;
use App\Exceptions\ValidationException;
use App\Src\Application\Bus\SimpleBusCommand;
use App\Src\Application\Bus\ValidatorBusCommand;
use App\Src\Application\Utils\Inflector;
use App\Src\Domain\UserCommand;
use Illuminate\Container\Container;

class TestController extends Controller
{
    public function create()
    {
        $command = new UserCommand('update',['id'=>7,'email' => 'penna@localhost.com', 'name' => 'penna', 'password' => 'penna123']);
        $validatorBus = new ValidatorBusCommand(new Container(), Inflector::singleton(), $this->bus);
        $result = $validatorBus->execute($command);
        return response([
            'status'=>'success',
            'data'=>$result
        ]);
    }

    public function show($id)
    {
        $command = new UserCommand('show',  ['id'=>$id]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'data' => $result
        ]);
    }
}

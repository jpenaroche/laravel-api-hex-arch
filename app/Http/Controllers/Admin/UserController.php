<?php

namespace App\Http\Controllers\Admin;

use App\Src\Application\Bus\ValidatorBusCommand;
use App\Src\Application\Utils\Inflector;
use App\Src\Domain\Models\User;
use App\Src\Domain\UserCommand;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

class UserController extends AdminBaseController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->section = 'usuarios';
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Responses
     */
    public function index()
    {
        $command = new UserCommand( 'list');
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result'=>$result,
            'section' => $this->section
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData= collect(json_decode($request->get('data'),true))->recursive();

        $command = new UserCommand('create',$requestData->except(['roles','permissions'])->toArray());
        $command->setRoles($requestData->get('roles')->pluck('id')->toArray());
        $command->setPermissions($requestData->get('permissions')->pluck('id')->toArray());
        if($request->has('medias'))
            $command->setMedias($request->all()['medias']??[]);
        $validatorBus = new ValidatorBusCommand(new Container(), Inflector::singleton(), $this->bus);
        $result = $validatorBus->execute($command);
        return response([
            'status'=>'success',
            'result'=>$result,
            'message'=>__('messages.success.saved')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command = new UserCommand('show',['id'=>$id]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Obtengo los datos desde el request y parseo el json data
        $requestData= collect(json_decode($request->get('data'),true))->recursive();

        $command = new UserCommand('update',$requestData->except(['roles','permissions'])->toArray());

        $command->setRoles($requestData->get('roles')->pluck('id')->toArray());
        $command->setPermissions($requestData->get('permissions')->pluck('id')->toArray());
        if($request->has('medias'))
            $command->setMedias($request->all()['medias']??[]);
        $validatorBus = new ValidatorBusCommand(new Container(), Inflector::singleton(), $this->bus);
        $result = $validatorBus->execute($command);
        return response([
            'status'=>'success',
            'id'=>$id,
            'result'=>$result,
            'message'=>__('messages.success.updated')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = new UserCommand('delete',['id'=>$id]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result,
            'message'=>__('messages.success.deleted')
        ]);
    }

    /**
     * Toggle Boolean Field on a resource.
     *
     * @param $field
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function toggle($field,Request $request)
    {
        $command = new UserCommand('toggle',['field'=>$field,'ids'=>$request->data]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result,
            'message'=>__('messages.success.toggled',['field'=> $field])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function medias($id,Request $request)
    {
        $command = new UserCommand('medias',['id'=>$id,'tags'=>$request->tags,'match'=>$request->match??0]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        $command = new UserCommand('saveOrder',['items'=>$request->order]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result->pluck('pos','id'),
            'message'=>__('messages.success.order_saved')
        ]);
    }
}

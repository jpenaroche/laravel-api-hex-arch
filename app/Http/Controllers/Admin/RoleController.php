<?php

namespace App\Http\Controllers\Admin;

use App\Src\Application\Bus\ValidatorBusCommand;
use App\Src\Application\Utils\Inflector;
use App\Src\Domain\RoleCommand;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

class RoleController extends AdminBaseController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->section = 'roles';
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Responses
     */
    public function index()
    {
        $command = new RoleCommand( 'list');
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
        $requestData= collect($request->all())->recursive();
        $command = new RoleCommand('create',$requestData->except('permissions')->toArray());
        $command->setPermissions($requestData->get('permissions')->pluck('id')->toArray());
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
        $command = new RoleCommand('show',['id'=>$id]);
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
        $requestData= collect($request->all())->recursive();
        $command = new RoleCommand('update',$requestData->except('permissions')->toArray());
        $command->setPermissions($requestData->get('permissions')->pluck('id')->toArray());
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
        $command = new RoleCommand('delete',['id'=>$id]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result,
            'message'=>__('messages.success.deleted',1)
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
        $command = new RoleCommand('toggle',['field'=>$field,'ids'=>$request->data]);
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        $command = new RoleCommand('saveOrder',['items'=>$request->order]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result,
            'message'=>__('messages.success.order_saved')
        ]);
    }
}

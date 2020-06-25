<?php

namespace App\Http\Controllers\Admin;

use App\Src\Application\Bus\ValidatorBusCommand;
use App\Src\Application\Utils\Inflector;
use App\Src\Domain\PermissionCommand;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

class PermissionController extends AdminBaseController
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
        $command = new PermissionCommand( 'list');
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result'=>$result,
            'section' => $this->section
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
        $command = new PermissionCommand('delete',['id'=>$id]);
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
        $command = new PermissionCommand('toggle',['field'=>$field,'ids'=>$request->data]);
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
        $command = new PermissionCommand('saveOrder',['items'=>$request->order]);
        $result = $this->bus->execute($command);
        return response([
            'status'=>'success',
            'result' => $result,
            'message'=>__('messages.success.order_saved')
        ]);
    }
}

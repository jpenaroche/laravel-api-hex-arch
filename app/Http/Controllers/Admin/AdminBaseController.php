<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminBaseController extends Controller
{
    protected $section;

    /**
     * AdminBaseController constructor.
     */
    public function __construct()
    {
        $this->middleware("permission:listar-{$this->section}|asignar-{$this->section}")->only('index');
        $this->middleware("permission:mostrar-{$this->section}")->only('show');
        $this->middleware("permission:crear-{$this->section}")->only('store');
        $this->middleware("permission:actualizar-{$this->section}")->only('update','toggle');
        $this->middleware("permission:borrar-{$this->section}")->only('delete');
        parent::__construct();
    }

    public function uploadMedias(Request $request){
        /* Handle uploaded medias with plank/mediable */
    }

    public function sort(){
        /* Handle sort models */
    }
}

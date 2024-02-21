<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ConfiguracionesController extends Controller
{
    function __construct()
    {
        $this->middleware('can:configuraciones.index')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('configuraciones.index');
    }
}

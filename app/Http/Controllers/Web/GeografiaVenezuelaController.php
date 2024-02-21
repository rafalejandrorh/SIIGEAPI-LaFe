<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Geografia;

class GeografiaVenezuelaController extends Controller
{
  private $geografia;

  function __construct(Geografia $geografia)
  {
    $this->geografia = $geografia;
  }

  public function get($id_padre, $id_hijo)
  {
    $data = $this->geografia->IdHijo($id_hijo)->IdPadre($id_padre)->select('valor','id')->get();
    return response()->json($data);
  } 
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sessions;
use App\Models\User;
use App\Traits\HistorialAccionesTrait;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HomeController extends Controller
{
    use HistorialAccionesTrait;

    private $resenna;
    private $session;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Sessions $session, User $user)
    {
        $this->middleware('auth');

        $this->session = $session;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = $this->getHistorialAcciones();
        return view('home', compact('data'));
    }
}

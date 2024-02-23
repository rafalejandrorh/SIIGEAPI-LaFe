<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Historial_Sesion;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use App\Events\LoginHistorialEvent;
use App\Events\LogoutHistorialEvent;
use App\Models\Questions;
use App\Models\Sessions;
use App\Models\User;
use App\Models\Users_Questions;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    private $cookieRemember = null;
    private $cookieUser = null;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'users';
    }

    public function index()
    {
        return view('auth.login');
    }

    ///////////////////////////////////////////////////////////////////////

    public function login(Request $request)
    {
        $userValidation = User::Where('users', $request->users)->exists();

        if($userValidation) {
            $users = User::Where('users', $request->users)->first();
            $id_user = $users['id'];
            $password = $users['password'];

            $sessionValidation = Sessions::Where('user_id', $id_user)->exists();
            if(!$sessionValidation) {  

                $passwordValidation = Hash::check(request('password'), $password);
                if($passwordValidation) {
                    $this->validateLogin($request);
                    
                    // If the class is using the ThrottlesLogins trait, we can automatically throttle
                    // the login attempts for this application. We'll key this by the username and
                    // the IP address of the client making these requests into this application.
                    if (method_exists($this, 'hasTooManyLoginAttempts') &&
                        $this->hasTooManyLoginAttempts($request)) {
                        $this->fireLockoutEvent($request);

                        return $this->sendLockoutResponse($request);
                    }

                    if ($this->attemptLogin($request)) {
                        if ($request->hasSession()) {
                            $request->session()->put('auth.password_confirmed_at', time());
                        }
                        return $this->sendLoginResponse($request, $id_user);
                    }

                    // If the login attempt was unsuccessful we will increment the number of attempts
                    // to login and redirect the user back to the login form. Of course, when this
                    // user surpasses their maximum number of attempts they will get locked out.
                    $this->incrementLoginAttempts($request);

                    return $this->sendFailedLoginResponse($request);
                }else{
                    Alert()->warning('Contraseña Incorrecta');
                }
            }else{
                Alert()->warning('El Usuario ya posee una sesión activa');
            }
        }else{
            Alert()->warning('Usuario Incorrecto');
        }
        return back();
    }

    public function questions($id_user)
    {
        $question = Users_Questions::join('questions', 'questions.id', '=', 'users_questions.id_questions')
        ->where('id_users', '=', $id_user)->select('questions.question', 'users_questions.response', 'users_questions.id')
        ->orderByRaw("random()")->limit(1)->get();

        return view('auth.login_questions', compact('question'));
    }

    public function validateQuestions($id_user)
    {
        return User::where('id', '=', $id_user)->where('security_questions', true)->exists();
    }

    public function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials = Arr::add($credentials, 'status', 'true');
        return $credentials ;
    }

    public function sendLoginResponse(Request $request, $id_user)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $response = $this->authenticated($request, $this->guard()->user());

        $this->setCookies($request);

        if($this->validateQuestions($id_user)) {

            $usersQuestions = Users_Questions::where('id_users', '=', $id_user)->exists();
            if(!$usersQuestions) {
                $questions = new Questions();
                $question1 = $questions->Where('id_padre', 10000)->pluck('question', 'id')->all();
                $question2 = $questions->Where('id_padre', 20000)->pluck('question', 'id')->all();
                $question3 = $questions->Where('id_padre', 30000)->pluck('question', 'id')->all();
        
                return view('auth.create_login_questions', compact('id_user', 'question1', 'question2', 'question3'));
            }
            return redirect()->route('questions.index', $id_user);
        }else{
            Alert()->toast('Inicio de Sesión Exitoso','success');
            return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath())->withCookies([
                $this->cookieRemember,
                $this->cookieUser
            ]);
        }
    }

    private function setCookies(Request $request)
    { 
        $remember = $request->remember;
        $this->cookieRemember = ($remember === 'on') ? cookie()->forever('remember', $remember) : cookie()->forget('remember');
        $this->cookieUser = ($remember === 'on') ? cookie()->forever('users', $request->users) : cookie()->forget('users');
    }

    public function authenticated(Request $request, $user)
    {
        if(session('id_historial_session') != null) {
            $sesion = Historial_Sesion::find(session('id_historial_sesion'), ['id']);
            $sesion->logout = now();
            $sesion->save();
            session()->forget('id_historial_sesion');
        };
        $explode = explode(' ', exec('getmac'));
        $MAC = $explode[0];

        $id_historial_sesion = event(new LoginHistorialEvent($user->id, $MAC));
        session(['id_historial_sesion' => $id_historial_sesion]);
    }

    public function logout(Request $request)
    {
        event(new LogoutHistorialEvent(session('id_historial_sesion'), $request->id, Auth::user()->id));
        session()->forget('id_historial_sesion');

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        if($request->id == 1) {
            Alert()->toast('Haz cerrado sesión en el Sistema','info');
        }else if($request->id == 2) {
            Alert()->toast('Cierre de Sesión por período de Inactividad','info');
        }else if($request->id == 3) {
            Alert()->warning('Se ha bloqueado tu Usuario', 'Respuesta Incorrecta a tu pregunta de Seguridad');
        }else if($request->id == 4) {
            Alert()->warning('Se Inactivó tu Usuario', 'No podrás acceder al Sistema');
        }
        return redirect('/');
    }
}

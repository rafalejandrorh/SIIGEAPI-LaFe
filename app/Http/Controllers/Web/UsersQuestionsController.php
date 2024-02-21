<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Events\TrazasEvent;
use App\Http\Constants;
use App\Models\Users_Questions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class UsersQuestionsController extends Controller
{
    private $users_questions;
    private $user;

    public function __construct(Users_Questions $users_questions, User $user)
    {
        $this->users_questions = $users_questions;
        $this->user = $user;
    }
    public function index(Request $request)
    {
        $question = $this->users_questions->join('nomenclador.questions', 'questions.id', '=', 'users_questions.id_questions')
        ->where('id_users', $request->user)->select('questions.question', 'users_questions.response', 'users_questions.id')
        ->orderByRaw("random()")->limit(1)->get();

        return view('auth.login_questions', compact('question'));
    }

    public function validation(Request $request)
    {
        $id_user = Auth::user()->id;
        $validation_question = $this->users_questions->Where('id', $request->id_question)->first();

        if($validation_question['response'] == $request->question) {
            
            $password_status = Auth::user()->password_status;
            if($password_status) {
                Alert()->warning('Atención', 'Por Razones de Seguridad, debe cambiar su contraseña.');
                return app(UserController::class)->profile($password_status);
            }else{
                Alert()->toast('Inicio de Sesión Exitoso','success');
                return redirect()->route('home');
            }
            
        }else{
            $user = $this->user->Where('id', $id_user)->first();
            $estatus = $user['status'] ? false : true;

            $users = $this->user->find($id_user, ['id']);
            $users->update(['status' => $estatus]);

            $id_Accion = Constants::ACTUALIZACION;
            $nuevoEstatus = $estatus ? 'Activo' : 'Inactivo'; 
            $valores_modificados = 'Datos de Usuario: '.$user['users'].' || '.$nuevoEstatus;
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

            $request['id'] = 3;
            return redirect()->route('logout.forced', $request['id']);
        }
    }

    public function store(Request $request)
    {
        $questions = [
            0 => [
                'question' => $request->question1,
                'response' => $request->response1,
                'padre' => 10000
            ],
            1 => [
                'question' => $request->question2,
                'response' => $request->response2,
                'padre' => 20000
            ],
            2 => [
                'question' => $request->question3,
                'response' => $request->response3,
                'padre' => 30000
            ]
        ];

        $i = 0;
        while($i<count($questions)) {
            $this->users_questions->create([
                'id_users' => $request->id_user,
                'id_questions' => $questions[$i]['question'],
                'response' => $questions[$i]['response'],
                'id_padre' => $questions[$i]['padre']
            ]);
            $i++;
        }

        $id_user = Auth::user()->id;
        $id_Accion = Constants::REGISTRO; 
        $valores_modificados = 'Se crearon las preguntas de Seguridad de este Usuario';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));
        
        Alert()->success('Preguntas de Seguridad Creadas Satisfactoriamente');
        return redirect()->route('login');
    }

    public function update(Request $request, $id)
    {
        $persona = $this->user->where('id', '=', $id)->first();
        $validacion_password = Hash::check(request('password'), $persona->password);
        if($validacion_password) {
            $questions = [
                0 => [
                    'question' => $request->question1,
                    'response' => $request->response1,
                    'padre'    => $request->padre1
                ],
                1 => [
                    'question' => $request->question2,
                    'response' => $request->response2,
                    'padre'    => $request->padre2
                ],
                2 => [
                    'question' => $request->question3,
                    'response' => $request->response3,
                    'padre'    => $request->padre3
                ]
            ];
    
            $i = 0;
            while($i<count($questions)) {
                $user_question = $this->users_questions->Where('id_users', $id)->Where('id_padre', $questions[$i]['padre']);
                $user_question->update([
                    'id_users' => $id,
                    'id_questions' => $questions[$i]['question'],
                    'response' => $questions[$i]['response'],
                ]);
                $i++;
            }
    
            $id_user = Auth::user()->id;
            $id_Accion = Constants::ACTUALIZACION; 
            $valores_modificados = 'Se actualizaron las preguntas de Seguridad de este Usuario';
            event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));

            Alert()->success('Preguntas de Seguridad Actualizadas Satisfactoriamente');
            return back();
        }else{
            Alert()->error('La Contraseña Actual indicada no coincide con nuestros registros.');
            return back();
        }
    }

    public function destroy($id)
    {
        $users_questions = $this->users_questions->Where('id_users', $id);
        $users_questions->delete();
        $id_user = Auth::user()->id;
        $id_Accion = Constants::ELIMINACION; 
        $valores_modificados = 'Se eliminaron las preguntas de Seguridad de este Usuario';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_User'));
        
        Alert()->success('Preguntas de Seguridad Eliminadas Satisfactoriamente');
        return back();
    }
}

<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_person',
        'users',
        'password',
        'status',
        'last_login',
        'password_status',
        'email',
        'security_questions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function persona()
    {
        return $this->belongsto(Person::class, 'id_person');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id');
    }

    public function session()
    {
        return $this->hasOne(Sessions::class, 'id');
    }

    public function search($request)
    {
        if(isset($request->buscador) && is_numeric($request->buscador))
        {
            if($request->tipo_busqueda == 'cedula'){
                $result = User::join('persons', 'persons.id', '=', 'users.id_person')
                ->select('users.id', 'users.id_person', 'users.users', 'users.status')
                ->Where('persons.cedula', '=', $request->buscador)->paginate(10);
            
            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = User::paginate(10);
            }
        }else if(isset($request->buscador) && is_string($request->buscador)){
            
            if($request->tipo_busqueda == 'usuario'){
                $result = User::select('users.id', 'users.id_person', 'users.users', 'users.status')
                ->Where('users', 'ilike', '%'.$request->buscador.'%')
                ->paginate(10);

            }else if($request->tipo_busqueda == 'estatus'){
                if($request->buscador == 'activo' || $request->buscador == 'Activo' || $request->buscador == 'ACTIVO'){
                    $status = true;
                }else if($request->buscador == 'inactivo' || $request->buscador == 'Inactivo' || $request->buscador == 'INACTIVO'){
                    $status = false;
                }
                $result = User::select('users.id', 'users.id_person', 'users.users', 'users.status')
                ->Where('status', '=', $status)
                ->paginate(10);

            }else if($request->tipo_busqueda == 'nombre'){
                $result = User::join('persons', 'persons.id', '=', 'users.id_person')
                ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
                ->Where('persons.primer_nombre', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            }else if($request->tipo_busqueda == 'apellido'){
                $result = User::join('persons', 'persons.id', '=', 'users.id_person')
                ->select('users.id', 'users.id_funcionario', 'users.users', 'users.status')
                ->Where('persons.primer_apellido', 'ilike', '%'.$request->buscador.'%')->paginate(10);

            }else{
                Alert()->warning('Búsqueda no permitida');
                $result = User::paginate(10);
            }
        }else{
            $result = User::paginate(10);
        }
        return $result;
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    static function returnValidations(){

        return $validations=[
            'users'    => 'required|max:20|unique:users',
            'password' => 'required|min:6',
        ];
        
    }

    static function  returnMessages(){

        return $messages=[
            'users.unique' =>'Nombre de usuario en uso, ingrese otro por favor!',
            'password.min' =>'Tu contraseña debe ser de mínimo 6 caracteres.',
        ];
    } 
}
